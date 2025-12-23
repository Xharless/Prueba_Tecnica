<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

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

const stats = [
    { number: '2,500+', label: 'Creadores Activos' },
    { number: '50K+', label: 'Donaciones Recibidas' },
    { number: '98%', label: 'Satisfacción' },
];

const steps = [
    { number: '01', title: 'Crea tu Cuenta', description: 'Regístrate en segundos con tu email', icon: '📝' },
    { number: '02', title: 'Personaliza tu Perfil', description: 'Añade tu foto, bio y enlaces sociales', icon: '🎨' },
    { number: '03', title: 'Comparte tu Enlace', description: 'Distribuye tu página entre tu audiencia', icon: '🔗' },
    { number: '04', title: 'Recibe Apoyos', description: 'Gana mientras ayudas a tu comunidad', icon: '☕' },
];

const features = [
    { icon: '🎨', title: 'Perfil Personalizable', description: 'Configura tu avatar, biografía y enlaces sociales en segundos. Tu página, tu estilo.' },
    { icon: '💸', title: 'Donaciones Directas', description: 'Recibe apoyos económicos (simulados) de tu audiencia de manera fácil y rápida.' },
    { icon: '⚡', title: 'Panel de Control', description: 'Visualiza tus ganancias y mensajes de apoyo desde un dashboard intuitivo.' },
    { icon: '📊', title: 'Estadísticas en Tiempo Real', description: 'Sigue tu progreso con gráficos detallados y análisis de tus donaciones.' },
    { icon: '🔒', title: '100% Seguro', description: 'Tus datos están protegidos con encriptación de nivel empresarial.' },
    { icon: '⚙️', title: 'Fácil de Usar', description: 'Interfaz intuitiva que no requiere conocimientos técnicos.' },
];

const testimonials = [
    { name: 'Alex Rivera', role: 'Creador de Contenido', text: 'eSponsor Clone cambió mi forma de monetizar. Súper fácil y confiable.', avatar: '👨‍💻' },
    { name: 'María Gómez', role: 'Streamer', text: 'Mis fans aman poder apoyarme directamente. ¡Recomendado 100%!', avatar: '👩‍🎨' },
    { name: 'Carlos López', role: 'Músico Independiente', text: 'Finalmente puedo vivir de mi música gracias a esto.', avatar: '🎵' },
];

const faqs = [
    { q: '¿Es gratis crear una cuenta?', a: 'Sí, es completamente gratis. Solo necesitas un email válido para empezar.' },
    { q: '¿Hay comisiones en las donaciones?', a: 'No, recibes el 100% de las donaciones. Sin comisiones ocultas.' },
    { q: '¿Puedo cambiar mi perfil después?', a: 'Por supuesto, puedes actualizar tu información cuando quieras desde tu dashboard.' },
    { q: '¿Es seguro para donatarios?', a: 'Sí, usamos encriptación y protecciones estándar de la industria.' },
];

const openFaq = ref(null);
</script>

<template>
    <Head title="Bienvenido a eSponsor Clone" />

    <div class="min-h-screen bg-black text-white selection:bg-pink-500 selection:text-white font-sans overflow-hidden">
        
        <!-- Fondos animados -->
        <div class="fixed top-0 left-1/4 w-96 h-96 bg-pink-600/20 rounded-full blur-[128px] pointer-events-none animate-pulse"></div>
        <div class="fixed bottom-0 right-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-[128px] pointer-events-none animate-pulse" style="animation-delay: 1s;"></div>

        <!-- Navbar -->
        <nav class="relative z-50 px-6 py-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center gap-2 group cursor-pointer">
                <span class="text-2xl group-hover:scale-110 transition duration-300">☕</span>
                <span class="font-bold text-xl tracking-tight group-hover:text-pink-400 transition">eSponsor Clone</span>
            </div>

            <div v-if="canLogin" class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="text-sm font-semibold text-gray-300 hover:text-white transition hover:scale-105"
                >
                    Ir al Dashboard
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="text-sm font-semibold text-gray-300 hover:text-white transition hover:scale-105"
                    >
                        Iniciar Sesión
                    </Link>

                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-5 py-2 rounded-full bg-white text-black font-bold text-sm hover:bg-gray-200 transition transform hover:scale-105 shadow-lg hover:shadow-xl"
                    >
                        Crear Cuenta
                    </Link>
                </template>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative z-10 max-w-7xl mx-auto px-6 py-16 lg:py-24">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                <div class="flex-1 text-center lg:text-left">
                    <div class="inline-block px-3 py-1 mb-4 border border-pink-500/30 rounded-full bg-pink-500/10 text-pink-400 text-xs font-bold tracking-wider uppercase">
                        ✨ Plataforma de Apoyo Directo
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight mb-6">
                        Tu creatividad <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 via-rose-500 to-orange-500">
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
                            class="px-8 py-4 rounded-full bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold text-lg shadow-lg shadow-pink-500/25 hover:shadow-pink-500/40 transition transform hover:-translate-y-1 active:scale-95"
                        >
                            Comenzar Ahora →
                        </Link>
                        <a href="#features" class="px-8 py-4 rounded-full border-2 border-gray-700 text-gray-300 font-semibold hover:bg-gray-900 hover:border-gray-600 transition transform hover:scale-105">
                            Saber más
                        </a>
                    </div>
                </div>

                <!-- Mockup -->
                <div class="flex-1 w-full max-w-md lg:max-w-full relative">
                    <div class="relative z-10 bg-[#0d1117] border border-gray-800 rounded-3xl p-6 shadow-2xl transform hover:scale-105 transition duration-500">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 p-1 shadow-lg">
                                <div class="h-full w-full rounded-full bg-gray-900 flex items-center justify-center text-2xl">👤</div>
                            </div>
                            <div>
                                <div class="h-4 w-32 bg-gray-700 rounded mb-2 animate-pulse"></div>
                                <div class="h-3 w-20 bg-gray-800 rounded animate-pulse" style="animation-delay: 0.2s;"></div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-12 w-full bg-[#161b22] rounded-xl border border-gray-800 flex items-center px-4 hover:border-pink-500/50 transition">
                                <div class="h-2 w-24 bg-gray-700 rounded"></div>
                            </div>
                            <div class="h-12 w-full bg-[#161b22] rounded-xl border border-gray-800 flex items-center px-4 hover:border-pink-500/50 transition">
                                <div class="h-2 w-32 bg-gray-700 rounded"></div>
                            </div>
                            <div class="h-14 w-full bg-gradient-to-r from-pink-500 to-rose-600 rounded-xl flex items-center justify-center mt-4 cursor-pointer hover:shadow-lg hover:shadow-pink-500/50 transition transform hover:scale-105">
                                <span class="text-white font-bold">☕ Invítame un café</span>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-4 -right-4 w-full h-full bg-gray-800/50 rounded-3xl -z-10"></div>
                </div>
            </div>
        </main>

        <!-- Estadísticas -->
        <section class="py-20 border-t border-gray-900/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid md:grid-cols-3 gap-8">
                    <div v-for="stat in stats" :key="stat.label" class="text-center group cursor-pointer">
                        <div class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-600 mb-2 group-hover:scale-110 transition duration-300">
                            {{ stat.number }}
                        </div>
                        <p class="text-gray-400 group-hover:text-gray-300 transition">{{ stat.label }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cómo Funciona -->
        <section class="py-24 border-t border-gray-900/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">Comienza en 4 pasos simples</h2>
                    <p class="text-gray-400 text-lg">Setup rápido para empezar a recibir apoyos</p>
                </div>
                
                <div class="grid md:grid-cols-4 gap-8">
                    <div v-for="(step, idx) in steps" :key="idx" class="relative group">
                        <div class="text-6xl mb-4 transform group-hover:scale-125 group-hover:-rotate-12 transition duration-300">{{ step.icon }}</div>
                        <div class="text-sm font-bold text-pink-500 mb-2">PASO {{ step.number }}</div>
                        <h3 class="text-xl font-bold mb-3 group-hover:text-pink-400 transition">{{ step.title }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ step.description }}</p>
                        
                        <div v-if="idx < steps.length - 1" class="hidden md:block absolute -right-4 top-12 w-8 h-1 bg-gradient-to-r from-pink-500 to-transparent"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section id="features" class="py-24 border-t border-gray-900/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">Todo lo que necesitas</h2>
                    <p class="text-gray-400 text-lg">Herramientas poderosas para potenciar tu creatividad</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div 
                        v-for="feature in features" 
                        :key="feature.title"
                        class="p-8 rounded-2xl bg-gradient-to-br from-[#0d1117] to-[#0a0e13] border border-gray-800 hover:border-pink-500/50 hover:shadow-lg hover:shadow-pink-500/20 transition duration-300 group cursor-pointer transform hover:scale-105 hover:-translate-y-2"
                    >
                        <div class="text-4xl mb-4 transform group-hover:scale-125 group-hover:rotate-12 transition duration-300">{{ feature.icon }}</div>
                        <h3 class="text-xl font-bold mb-3 group-hover:text-pink-400 transition">{{ feature.title }}</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonios -->
        <section class="py-24 border-t border-gray-900/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">Lo que dicen nuestros usuarios</h2>
                    <p class="text-gray-400 text-lg">Historias reales de creadores que ganaron</p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div 
                        v-for="testimonial in testimonials" 
                        :key="testimonial.name"
                        class="p-8 rounded-2xl bg-[#0d1117] border border-gray-800 hover:border-pink-500/50 transition duration-300 transform hover:scale-105 hover:-translate-y-2"
                    >
                        <div class="flex items-center gap-4 mb-6">
                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center text-2xl shadow-lg">
                                {{ testimonial.avatar }}
                            </div>
                            <div>
                                <p class="font-bold text-white">{{ testimonial.name }}</p>
                                <p class="text-sm text-gray-400">{{ testimonial.role }}</p>
                            </div>
                        </div>
                        <p class="text-gray-300 leading-relaxed italic">{{ testimonial.text }}</p>
                        <div class="mt-4 text-pink-500">★★★★★</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="py-24 border-t border-gray-900/50">
            <div class="max-w-2xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold mb-4">Preguntas Frecuentes</h2>
                    <p class="text-gray-400 text-lg">Todo lo que necesitas saber</p>
                </div>
                
                <div class="space-y-4">
                    <div 
                        v-for="(faq, idx) in faqs" 
                        :key="idx"
                        class="border border-gray-800 rounded-xl overflow-hidden hover:border-pink-500/50 transition duration-300"
                    >
                        <button
                            @click="openFaq = openFaq === idx ? null : idx"
                            class="w-full p-6 flex items-center justify-between bg-[#0d1117] hover:bg-[#161b22] transition duration-300 cursor-pointer group"
                        >
                            <span class="text-left font-semibold text-lg group-hover:text-pink-400 transition">{{ faq.q }}</span>
                            <span class="text-2xl text-pink-500 transform transition duration-300" :class="{ 'rotate-180': openFaq === idx }">+</span>
                        </button>
                        
                        <transition name="expand">
                            <div v-show="openFaq === idx" class="px-6 py-4 bg-[#161b22] border-t border-gray-800">
                                <p class="text-gray-300 leading-relaxed">{{ faq.a }}</p>
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Final -->
        <section class="py-24 border-t border-gray-900/50">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-5xl font-bold mb-6">¿Listo para monetizar tu creatividad?</h2>
                <p class="text-xl text-gray-400 mb-8">Únete a miles de creadores que ya están ganando. Es completamente gratis.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="px-10 py-5 rounded-full bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold text-lg shadow-lg shadow-pink-500/30 hover:shadow-pink-500/50 transition transform hover:-translate-y-1 active:scale-95"
                    >
                        Crear Cuenta Gratis
                    </Link>
                    <a href="#features" class="px-10 py-5 rounded-full border-2 border-gray-700 text-gray-300 font-semibold hover:bg-gray-900 hover:border-gray-600 transition transform hover:scale-105">
                        Ver características
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-12 text-center text-gray-500 text-sm border-t border-gray-900/50">
            <div class="max-w-7xl mx-auto px-6">
                <p class="mb-4">&copy; 2024 eSponsor Clone. Desarrollado con ❤️ para creadores.</p>
                <p class="text-xs text-gray-600">Laravel v{{ laravelVersion }} & PHP v{{ phpVersion }}</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
@keyframes expand {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
    }
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.3s ease;
}

.expand-enter-from,
.expand-leave-to {
    opacity: 0;
    max-height: 0;
}
</style>