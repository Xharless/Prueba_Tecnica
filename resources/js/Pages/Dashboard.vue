<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    supports: {
        type: Array,
        default: () => [],
    },
});

const totalEarned = computed(() => {
    return props.supports.reduce((sum, support) => sum + parseFloat(support.amount), 0);
});

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(value);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('es-CL', { 
        year: 'numeric', month: 'short', day: 'numeric' 
    });
};
</script>

<template>
    <Head title="Panel de Control" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2 class="text-2xl md:text-3xl font-bold leading-tight text-gray-900 dark:text-white">
                    Resumen Financiero
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Monitorea tus apoyos y ganancias
                </p>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-12">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Recaudado
                                </p>
                                <p class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                                    {{ formatCurrency(totalEarned) }}
                                </p>
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                    En {{ supports.length }} apoyos
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-2xl p-6 sm:p-8 shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Apoyos Recibidos
                                </p>
                                <p class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white">
                                    {{ supports.length }}
                                </p>
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                    Donaciones totales
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-12 w-12 rounded-xl bg-pink-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">
                            Historial de Donaciones
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Detalle de todos los mensajes y montos recibidos.
                        </p>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fan</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mensaje</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                                <tr v-for="support in supports" :key="support.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold text-sm border border-indigo-200 dark:border-indigo-800">
                                                {{ support.supporter_name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ support.supporter_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-600 dark:text-gray-300 italic max-w-xs truncate">
                                            "{{ support.message || 'Sin mensaje' }}"
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ formatDate(support.created_at) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                            + {{ formatCurrency(support.amount) }}
                                        </span>
                                    </td>
                                </tr>

                                <tr v-if="supports.length === 0">
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                            <svg class="h-10 w-10 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-sm">No hay registros aún. ¡Comparte tu perfil!</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>