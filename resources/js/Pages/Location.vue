<script setup>
import { onMounted, watch, ref } from "vue";
import { useForm, router } from '@inertiajs/vue3';
import L from "leaflet";
import AppLayout from '@/Layouts/AppLayout.vue';

let map;
const selectedCategory = ref("");
let newLocation = useForm({
    name: "",
    description: "",
    category: "",
    latitude: "",
    longitude: "",
});

let props = defineProps({
    locations: Array,
})

watch(() => props.locations, (locations) => {
    showLocations(locations);
}, { deep: true });

onMounted(() => {
    map = L.map('map').setView([51.505, -0.09], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    showLocations(props.locations);

    let customIcon = L.icon({
        iconUrl: "images/pin.png",
        iconSize: [38, 38],
        iconAnchor: [19, 38],
    });
    let currentMarker = null;

    map.on('click', (event) => {
        newLocation.latitude = event.latlng.lat;
        newLocation.longitude = event.latlng.lng;

        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        currentMarker = L.marker([event.latlng.lat, event.latlng.lng], { icon: customIcon }).addTo(map);
    });

});

const showLocations = (locations) => {
    map.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
            map.removeLayer(layer);
        }
    });

    locations.forEach((element) =>
        L.marker([element.latitude, element.longitude])
            .addTo(map)
            .bindPopup("<b>" + element.name + "</b><br>" + element.description)
    );
}

const applyFilter = () => {
    router.get(route('locations.index'), { category: selectedCategory.value }, { preserveState: true });
};

const addLocation = async () => {
    newLocation.latitude = parseFloat(newLocation.latitude).toFixed(8);
    newLocation.longitude = parseFloat(newLocation.longitude).toFixed(8);
    newLocation.post(route('locations.store'), {
        preserveScroll: true,
        onSuccess: () => {
            newLocation.reset();
        }
    });
};

</script>

<style scoped>
#map {
    width: 100%;
    height: 500px;
    border-radius: 10px;
}
</style>

<template>
    <AppLayout title="Locations">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Locations
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Section Filtre -->
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <label for="category" class="block text-sm font-medium text-gray-700">
                        Filtrer par catégorie :
                    </label>
                    <select v-model="selectedCategory" @change="applyFilter"
                        class="mt-2 block w-full sm:w-auto px-4 py-2 border rounded-lg text-gray-700">
                        <option value="">Toutes les catégories</option>
                        <option value="park">Parc</option>
                        <option value="forest">Forêt</option>
                        <option value="river">Rivière</option>
                    </select>
                </div>

                <!-- Section Carte -->
                <div class="relative bg-white shadow sm:rounded-lg p-6">
                    <div class="h-96 w-full rounded-md overflow-hidden">
                        <div id="map" class="h-full w-full"></div>
                    </div>
                </div>

                <!-- Formulaire d'ajout -->
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ajouter une Location</h3>
                    <form @submit.prevent="addLocation" class="space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Nom -->
                            <div class="flex-1">
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nom :
                                </label>
                                <input v-model="newLocation.name" id="name" type="text"
                                    class="mt-2 block w-full px-4 py-2 border rounded-lg text-gray-700"
                                    placeholder="Nom de la location" required />

                                <div v-if="newLocation.errors.name" class="text-red-600">{{ newLocation.errors.name }}
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="flex-1">
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    Description :
                                </label>
                                <textarea v-model="newLocation.description" id="description"
                                    class="mt-2 block w-full px-4 py-2 border rounded-lg text-gray-700" rows="2"
                                    placeholder="Description..." required></textarea>

                                <div v-if="newLocation.errors.description" class="text-red-600">{{
                                    newLocation.errors.description }}</div>
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">
                                Catégorie :
                            </label>
                            <select v-model="newLocation.category" id="category"
                                class="mt-2 block w-full px-4 py-2 border rounded-lg text-gray-700" required>
                                <option disabled value="">Sélectionnez une catégorie</option>
                                <option value="park">Parc</option>
                                <option value="forest">Forêt</option>
                                <option value="river">Rivière</option>
                            </select>

                            <div v-if="newLocation.errors.category" class="text-red-600">{{ newLocation.errors.category
                            }}
                            </div>
                        </div>

                        <!-- Latitude & Longitude -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="sm:w-1/2">
                                <label for="latitude" class="block text-sm font-medium text-gray-700">
                                    Latitude :
                                </label>
                                <input v-model="newLocation.latitude" id="latitude" type="text"
                                    class="mt-2 block w-full px-4 py-2 border rounded-lg text-gray-700"
                                    placeholder="Exemple : 48.8566" required />
                                <div v-if="newLocation.errors.latitude" class="text-red-600">{{
                                    newLocation.errors.latitude
                                }}
                                </div>
                            </div>
                            <div class="sm:w-1/2">
                                <label for="longitude" class="block text-sm font-medium text-gray-700">
                                    Longitude :
                                </label>
                                <input v-model="newLocation.longitude" id="longitude" type="text"
                                    class="mt-2 block w-full px-4 py-2 border rounded-lg text-gray-700"
                                    placeholder="Exemple : 2.3522" required />
                                <div v-if="newLocation.errors.longitude" class="text-red-600">{{
                                    newLocation.errors.longitude }}
                                </div>
                            </div>
                        </div>

                        <!-- Bouton Ajouter -->
                        <div class="text-right">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded-lg">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AppLayout>
</template>
