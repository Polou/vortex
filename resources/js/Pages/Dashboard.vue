<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

let props = defineProps({
    locations: Array,
    userTeamId: Number,
    userTeamIds: Array,
})

const searchQuery = ref(""); // search query
// filter locations
const filteredLocations = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return props.locations.filter(location =>
        location.name.toLowerCase().includes(query) ||
        location.description.toLowerCase().includes(query)
    );
});

const editingId = ref(null); // locaiton id being edited
const editData = ref({ name: '', description: '', is_featured: 0 }); // temp location data to edit

const startEditing = (location) => {
    editingId.value = location.id;
    editData.value = { name: location.name, description: location.description, is_featured: location.is_featured };
};

const cancelEditing = () => {
    editingId.value = null;
    editData.value = { name: '', description: '', is_featured: 0 };
};

const saveLocation = (locationId) => {
    router.patch(route('locations.update', { location: locationId }), {
        name: editData.value.name,
        description: editData.value.description,
        is_featured: parseInt(editData.value.is_featured)
    }, {
        onSuccess: () => {
            editingId.value = null;
        },
    });
};

const deleteLocation = (locationId) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette location ?")) {
        router.delete(route('locations.delete', { location: locationId }));
    }

};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Liste des locations
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white shadow sm:rounded-lg p-6">

                    <div class="flex justify-end mb-4">
                        <input type="text" v-model="searchQuery" placeholder="Rechercher..."
                            class="w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Nom</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">
                                        Description</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Catégorie
                                    </th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Latitude
                                    </th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Longitude
                                    </th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700 border-b">Featured
                                    </th>
                                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700 border-b">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-gray-50" v-for="location in filteredLocations" :key="location.id">
                                    <td class="px-4 py-2 text-sm text-gray-800 border-b">
                                        <div v-if="editingId === location.id">
                                            <input v-model="editData.name" type="text"
                                                class="w-full px-2 py-1 border rounded text-gray-700" />
                                        </div>
                                        <div v-else>{{ location.name }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800 border-b">
                                        <div v-if="editingId === location.id">
                                            <input v-model="editData.description" type="text"
                                                class="w-full px-2 py-1 border rounded text-gray-700" />
                                        </div>
                                        <div v-else>{{ location.description }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800 border-b">{{ location.category }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800 border-b">{{ location.latitude }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800 border-b">{{ location.longitude }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-800 border-b">
                                        <div v-if="editingId === location.id && location.userCanDelete">
                                            <select v-model="editData.is_featured"
                                                class="w-full px-2 py-1 border rounded text-gray-700">
                                                <option value="1">Oui</option>
                                                <option value="0">Non</option>
                                            </select>
                                        </div>
                                        <div v-else>{{ location.is_featured ? 'Oui' : 'Non' }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-center text-sm text-gray-800 border-b">
                                        <div class="flex justify-center gap-2">
                                            <!-- Mode édition activé -->
                                            <template v-if="editingId === location.id">
                                                <button @click="saveLocation(location.id)"
                                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded text-sm">
                                                    Sauvegarder
                                                </button>
                                                <button @click="cancelEditing"
                                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded text-sm">
                                                    Annuler
                                                </button>
                                            </template>
                                            <!-- Mode édition désactivé -->
                                            <template v-else>
                                                <button @click="startEditing(location)" v-if="location.userCanEdit"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">
                                                    Éditer
                                                </button>
                                                <button @click="deleteLocation(location.id)"
                                                    v-if="location.userCanDelete"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm">
                                                    Supprimer
                                                </button>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>