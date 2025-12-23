<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    links: Array,
    status: String,
});

const showDeleteConfirm = ref(null);

const form = useForm({
    title: '',
    url: '',
});

const submit = () => {
    form.post(route('links.store'), {
        onSuccess: () => form.reset(),
    });
};

const deleteLink = (link) => {
    form.delete(route('links.destroy', link.id), {
        onSuccess: () => showDeleteConfirm.value = null,
    });
};
</script>

<template>
    <Head title="Mis Enlaces" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Gestión de Enlaces</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section class="max-w-xl">
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Agregar nuevo enlace</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Añade un título y una URL válida para mostrar en tu perfil.</p>
                        </header>

                        <form @submit.prevent="submit" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="title" value="Título" />
                                <TextInput id="title" type="text" class="mt-1 block w-full" v-model="form.title" required />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div>
                                <InputLabel for="url" value="URL (https://...)" />
                                <TextInput id="url" type="url" class="mt-1 block w-full" v-model="form.url" required />
                                <InputError class="mt-2" :message="form.errors.url" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Guardar Link</PrimaryButton>
                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado correctamente.</p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Tus Enlaces Actuales</h3>
                    <div class="space-y-3">
                        <div v-for="link in links" :key="link.id" class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                            <div class="flex-1">
                                <p class="text-gray-900 dark:text-white font-semibold">{{ link.title }}</p>
                                <a :href="link.url" target="_blank" class="text-indigo-500 hover:underline text-sm">{{ link.url }}</a>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <a
                                    :href="route('links.edit', link.id)"
                                    class="px-3 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm rounded-lg transition font-medium"
                                >
                                    <span class="hidden sm:inline">Editar</span>
                                    <span class="sm:hidden">✏️</span>
                                </a>
                                <button
                                    type="button"
                                    @click="showDeleteConfirm = link.id"
                                    class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition font-medium"
                                >
                                    <span class="hidden sm:inline">Eliminar</span>
                                    <span class="sm:hidden">🗑️</span>
                                </button>
                            </div>
                        </div>
                        <p v-if="links.length === 0" class="text-gray-500 text-center italic">No has agregado enlaces todavía.</p>
                    </div>
                </div>

                <!-- Modal de confirmación de eliminación -->
                <Teleport to="body" v-if="showDeleteConfirm">
                    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-sm mx-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">¿Eliminar enlace?</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                ¿Estás seguro de que deseas eliminar este enlace? Esta acción no se puede deshacer.
                            </p>
                            <div class="flex gap-4">
                                <button
                                    type="button"
                                    @click="showDeleteConfirm = null"
                                    class="flex-1 px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-900 dark:text-white font-medium rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="button"
                                    @click="deleteLink(links.find(l => l.id === showDeleteConfirm))"
                                    class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition"
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </Teleport>

            </div>
        </div>
    </AuthenticatedLayout>
</template>