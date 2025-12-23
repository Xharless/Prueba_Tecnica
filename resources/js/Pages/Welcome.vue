<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Bienvenido" />

    <div class="min-h-screen bg-black text-white selection:bg-pink-500 selection:text-white font-sans overflow-hidden">
        
        <div class="fixed top-0 left-1/4 w-96 h-96 bg-pink-600/20 rounded-full blur-[128px] pointer-events-none"></div>
        <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[128px] pointer-events-none"></div>

        <nav class="relative z-50 px-6 py-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-2">
                <span class="text-2xl">☕</span>
                <span class="font-bold text-xl tracking-tight">eSponsor Clone</span>
            </div>

            <div v-if="canLogin" class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="text-sm font-semibold text-gray-300 hover:text-white transition"
                >
                    Ir al Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-sm font-semibold text-gray-300 hover:text-white transition"
                    >
                        Iniciar Sesión
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-5 py-2 rounded-full bg-white text-black font-bold text-sm hover:bg-gray-200 transition transform hover:scale-105"
                    >
                        Crear Cuenta
                    </Link>
                </template>
            </div>
        </nav>

        <main class="relative z-10 max-w-7xl mx-auto px-6 py-16 lg:py-24 flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            
            <div class="flex-1 text-center lg:text-left">
                <div class="inline-block px-3 py-1 mb-4 border border-pink-500/30 rounded-full bg-pink-500/10 text-pink-400 text-xs font-bold tracking-wider uppercase">
                    Plataforma de Donaciones
                </div>
                <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight mb-6">
                    Tu creatividad <br />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600">
                        merece apoyo.
                    </span>
                </h1>
                <p class="text-lg text-gray-400 mb-8 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    Crea tu página personal, comparte tus enlaces y recibe donaciones simbólicas de tus seguidores. Sin complicaciones, 100% gratuito.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-8 py-4 rounded-full bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold text-lg shadow-lg shadow-pink-500/25 hover:shadow-pink-500/40 transition transform hover:-translate-y-1"
                    >
                        Comenzar Ahora &rarr;
                    </Link>
                    <a href="#features" class="px-8 py-4 rounded-full border border-gray-700 text-gray-300 font-semibold hover:bg-gray-900 transition">
                        Saber más
                    </a>
                </div>
            </div>

            <div class="flex-1 w-full max-w-md lg:max-w-full relative">
                <div class="relative z-10 bg-[#0d1117] border border-gray-800 rounded-3xl p-6 shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 p-1">
                            <div class="h-full w-full rounded-full bg-gray-900"></div>
                        </div>
                        <div>
                            <div class="h-4 w-32 bg-gray-700 rounded mb-2"></div>
                            <div class="h-3 w-20 bg-gray-800 rounded"></div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="h-12 w-full bg-[#161b22] rounded-xl border border-gray-800 flex items-center px-4">
                            <div class="h-2 w-24 bg-gray-700 rounded"></div>
                        </div>
                        <div class="h-12 w-full bg-[#161b22] rounded-xl border border-gray-800 flex items-center px-4">
                            <div class="h-2 w-32 bg-gray-700 rounded"></div>
                        </div>
                        <div class="h-14 w-full bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl flex items-center justify-center mt-4">
                            <span class="text-white font-bold">☕ Invítame un café</span>
                        </div>
                    </div>
                </div>
                
                <div class="absolute top-4 -right-4 w-full h-full bg-gray-800/50 rounded-3xl -z-10 transform -rotate-2"></div>
            </div>
        </main>

        <section id="features" class="py-24 border-t border-gray-900">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-center mb-16">Todo lo que necesitas</h2>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-8 rounded-2xl bg-[#0d1117] border border-gray-800 hover:border-pink-500/50 transition duration-300">
                        <div class="h-12 w-12 rounded-lg bg-pink-500/10 flex items-center justify-center text-2xl mb-6">🎨</div>
                        <h3 class="text-xl font-bold mb-3">Perfil Personalizable</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Configura tu avatar, biografía y enlaces sociales en segundos. Tu página, tu estilo.</p>
                    </div>

                    <div class="p-8 rounded-2xl bg-[#0d1117] border border-gray-800 hover:border-pink-500/50 transition duration-300">
                        <div class="h-12 w-12 rounded-lg bg-pink-500/10 flex items-center justify-center text-2xl mb-6">💸</div>
                        <h3 class="text-xl font-bold mb-3">Donaciones Directas</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Recibe apoyos económicos (simulados) de tu audiencia de manera fácil y rápida.</p>
                    </div>

                    <div class="p-8 rounded-2xl bg-[#0d1117] border border-gray-800 hover:border-pink-500/50 transition duration-300">
                        <div class="h-12 w-12 rounded-lg bg-pink-500/10 flex items-center justify-center text-2xl mb-6">⚡</div>
                        <h3 class="text-xl font-bold mb-3">Panel de Control</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Visualiza tus ganancias y mensajes de apoyo desde un dashboard intuitivo.</p>
                    </div>
                </div>
            </div>
        </section>

        <footer class="py-12 text-center text-gray-500 text-sm border-t border-gray-900">
            <p>&copy; 2024 eSponsor Clone. Desarrollado con Laravel v{{ laravelVersion }} & PHP v{{ phpVersion }}.</p>
        </footer>
    </div>
</template>