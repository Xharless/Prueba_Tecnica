<script setup>
import { Head, useForm } from '@inertiajs/vue3';
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
            alert(`¡Gracias por el café! ☕ (${montoFormateado} simulados guardados)`);
        },
    });
};

const predefinedAmounts = [1000, 2000, 5000, 10000];

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP', maximumFractionDigits: 0 }).format(value);
};
</script>

<template>
    <Head :title="user.name" />

    <div class="min-h-screen bg-black flex items-center justify-center py-12 px-4 sm:px-6 font-sans">
        
        <div class="w-full max-w-md bg-[#0d1117] rounded-3xl shadow-2xl border border-gray-800 p-8 text-center relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-pink-500 to-rose-600"></div>

            <div class="relative z-10">
                
                <div class="flex justify-center mb-6 mt-2">
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-pink-500 to-rose-600 rounded-full blur opacity-40 group-hover:opacity-75 transition duration-500"></div>
                        <div class="relative h-28 w-28 rounded-full bg-[#0d1117] flex items-center justify-center text-white text-4xl font-bold overflow-hidden border-4 border-[#0d1117] shadow-lg">
                            <img v-if="user.avatar" :src="user.avatar" alt="Avatar" class="h-full w-full object-cover">
                            <span v-else class="text-gray-400 select-none">{{ user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-white tracking-tight mb-1">
                        {{ user.name }}
                    </h1>
                    <p class="text-sm font-semibold text-pink-500 mb-4">
                        @{{ user.username }}
                    </p>
                    <p class="text-gray-400 text-sm leading-relaxed mx-auto">
                        {{ user.bio || '¡Hola! Bienvenido a mi página de enlaces.' }}
                    </p>
                </div>

                <button 
                    @click="showingModal = true"
                    class="w-full group relative flex justify-center py-4 px-6 border border-transparent text-lg font-bold rounded-xl text-white bg-gradient-to-r from-pink-500 to-rose-600 hover:from-pink-600 hover:to-rose-700 shadow-lg hover:shadow-pink-500/30 transform transition-all duration-200 hover:-translate-y-1 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 mb-8"
                >
                    <span class="flex items-center gap-2">
                        ☕ Invítame un café
                    </span>
                </button>

                <div class="space-y-3 w-full">
                    <a v-for="link in user.links" :key="link.id" 
                       :href="link.url" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="group block w-full bg-[#161b22] border border-[#30363d] hover:border-pink-500 text-gray-200 font-semibold py-4 px-6 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-between"
                    >
                        <span class="w-6"></span>
                        <span class="truncate">{{ link.title }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-pink-500 transition-colors opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>

                    <div v-if="user.links.length === 0" class="py-6 text-gray-500 text-sm italic">
                        Sin enlaces públicos.
                    </div>
                </div>
                
                <div class="mt-10 pt-6 border-t border-gray-800">
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">
                        Creado con <span class="text-pink-500 font-bold">eSponsor Clone</span>
                    </p>
                </div>
            </div>
        </div>

        <div v-if="showingModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm transition-opacity" @click="showingModal = false"></div>

            <div class="relative bg-[#0d1117] rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 overflow-hidden transform transition-all scale-100 border border-gray-800">
                
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold text-white">Invita un café ☕</h3>
                    <p class="text-sm text-gray-400 mt-1">Apoya a {{ user.name }} con una donación.</p>
                </div>

                <form @submit.prevent="submitSupport">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Selecciona un monto (CLP)</label>
                        <div class="grid grid-cols-4 gap-2 mb-3">
                            <button 
                                type="button" 
                                v-for="amount in predefinedAmounts" 
                                :key="amount"
                                @click="form.amount = amount"
                                :class="{'bg-pink-600 text-white ring-2 ring-pink-300': form.amount === amount, 'bg-gray-800 text-gray-300 hover:bg-gray-700': form.amount !== amount}"
                                class="py-2 rounded-lg font-bold text-sm transition-all flex items-center justify-center border border-transparent"
                            >
                                ${{ amount / 1000 }}k
                            </button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-bold">$</span>
                            </div>
                            <input 
                                type="number" 
                                v-model="form.amount" 
                                class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-700 bg-[#161b22] font-bold text-white focus:ring-pink-500 focus:border-pink-500 text-lg placeholder-gray-600"
                                min="100"
                                step="100"
                            >
                        </div>
                        <p v-if="form.errors.amount" class="text-red-500 text-xs mt-1 text-center">{{ form.errors.amount }}</p>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Tu Nombre</label>
                            <input v-model="form.supporter_name" type="text" placeholder="Ej: Un Fan Anónimo" class="w-full rounded-lg border border-gray-700 bg-[#161b22] text-white focus:ring-pink-500 focus:border-pink-500 p-2.5 placeholder-gray-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Mensaje</label>
                            <textarea v-model="form.message" rows="2" placeholder="¡Sigue así!" class="w-full rounded-lg border border-gray-700 bg-[#161b22] text-white focus:ring-pink-500 focus:border-pink-500 p-2.5 placeholder-gray-600"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showingModal = false" class="flex-1 py-3 px-4 border border-gray-700 rounded-xl text-gray-300 font-semibold hover:bg-gray-800 transition">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="form.processing" class="flex-1 py-3 px-4 bg-gradient-to-r from-pink-500 to-rose-600 text-white rounded-xl font-bold shadow-lg hover:shadow-pink-500/30 transition transform hover:-translate-y-0.5 disabled:opacity-50 flex flex-col items-center justify-center gap-0.5">
                            <span v-if="!form.processing" class="text-sm">Donar</span>
                            <span v-if="!form.processing" class="text-base font-bold">{{ formatCurrency(form.amount) }}</span>
                            <span v-else class="text-base">Procesando...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>