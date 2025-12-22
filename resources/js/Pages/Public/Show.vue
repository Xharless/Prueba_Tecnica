<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object,
});

const showingModal = ref(false);

const form = useForm({
    amount: 1000,
    supporter_name: '',
    message: '',
});

const submitSupport = () => {
    form.post(route('supports.store', props.user.id), {
        onSuccess: () => {
            showingModal.value = false;
            form.reset();
            const montoFormateado = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(form.amount);
            alert("¡Gracias por el café! ☕ (Simulación guardada)");
        },
    });
};

const predefinedAmounts = [1000, 3000, 5000, 10000];
const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(value);
};
</script>

<template>
    <Head :title="user.name" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col items-center py-12 px-4 sm:px-6 lg:px-8 font-sans transition-colors duration-300">
        
        <div class="w-full max-w-md space-y-8 text-center z-0">
            <div class="flex justify-center">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-indigo-500 rounded-full blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                    <div class="relative h-28 w-28 rounded-full bg-indigo-500 flex items-center justify-center text-white text-4xl font-bold overflow-hidden border-4 border-white dark:border-gray-800 shadow-xl">
                        <img v-if="user.avatar" :src="user.avatar" alt="Avatar" class="h-full w-full object-cover">
                        <span v-else>{{ user.name.charAt(0) }}</span>
                    </div>
                </div>
            </div>

            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ user.name }}</h1>
                <p class="text-sm font-medium text-indigo-500 dark:text-indigo-400 mb-2">@{{ user.username }}</p>
                <p class="text-gray-600 dark:text-gray-300 max-w-xs mx-auto leading-relaxed">{{ user.bio }}</p>
            </div>

            <button 
                @click="showingModal = true"
                class="w-full group relative flex justify-center py-3.5 px-6 border border-transparent text-lg font-bold rounded-full text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-lg transform transition-all duration-200 hover:-translate-y-1 active:scale-95 focus:outline-none"
            >
                <span class="flex items-center gap-2">☕ Invítame un café</span>
            </button>

            <div class="space-y-4 w-full mt-8">
                <a v-for="link in user.links" :key="link.id" :href="link.url" target="_blank" class="group block w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 text-gray-800 dark:text-gray-100 font-semibold py-4 px-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-1 flex items-center justify-between">
                    <span class="w-6"></span>
                    <span class="truncate">{{ link.title }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
            
             <div class="mt-12 text-xs text-gray-400 font-medium">Creado con <span class="text-indigo-500">eSponsor Clone</span></div>
        </div>

        <div v-if="showingModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6">
            <div class="absolute inset-0 bg-black/50 transition-opacity" @click="showingModal = false"></div>

            <div class="relative bg-white/95 dark:bg-zinc-900/95 backdrop-blur-xl rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 overflow-hidden transform transition-all scale-100 border border-white/20 dark:border-zinc-700/50">
                
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Invita un café ☕</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Apoya a {{ user.name }} con una donación simbólica.</p>
                </div>

                <form @submit.prevent="submitSupport">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selecciona un monto ($)</label>
                        <div class="flex gap-2 justify-center">
                            <button 
                                type="button" 
                                v-for="amount in predefinedAmounts" 
                                :key="amount"
                                @click="form.amount = amount"
                                :class="{'bg-indigo-600 text-white ring-2 ring-indigo-300': form.amount === amount, 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600': form.amount !== amount}"
                                class="h-12 w-12 rounded-full font-bold transition-all flex items-center justify-center"
                            >
                                {{ amount }}
                            </button>
                            <input 
                                type="number" 
                                v-model="form.amount" 
                                class="h-12 w-20 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-center font-bold dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                min="1"
                            >
                        </div>
                        <p v-if="form.errors.amount" class="text-red-500 text-xs mt-1 text-center">{{ form.errors.amount }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tu Nombre</label>
                        <input 
                            v-model="form.supporter_name"
                            type="text" 
                            placeholder="Ej: Un Fan Anónimo"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        >
                        <p v-if="form.errors.supporter_name" class="text-red-500 text-xs mt-1">{{ form.errors.supporter_name }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mensaje (Opcional)</label>
                        <textarea 
                            v-model="form.message"
                            rows="3" 
                            placeholder="¡Sigue así con el buen contenido!"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        ></textarea>
                        <p v-if="form.errors.message" class="text-red-500 text-xs mt-1">{{ form.errors.message }}</p>
                    </div>

                    <div class="flex gap-3">
                        <button 
                            type="button" 
                            @click="showingModal = false"
                            class="flex-1 py-3 px-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-semibold hover:border-gray-400 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="flex-1 py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-bold shadow-lg hover:shadow-blue-500/30 transition transform hover:-translate-y-0.5 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Enviando...' : `Apoyar con $${form.amount}` }}
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</template>