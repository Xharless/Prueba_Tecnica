<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// --- NUEVAS IMPORTACIONES PARA EL CROPPER ---
import { Cropper, CircleStencil } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.auth.user;

const form = useForm({
    _method: 'PATCH',
    name: user.name,
    email: user.email,
    username: user.username || '',
    bio: user.bio || '',
    avatar: null,
});

// Variables para el manejo de imágenes
const photoPreview = ref(null);
const photoInput = ref(null);

// Variables para el Modal de Recorte
const showCropModal = ref(false);
const tempImageSrc = ref(null);
const cropperRef = ref(null); // Referencia al componente Cropper

// 1. Cuando el usuario selecciona un archivo
const onFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    // Convertimos el archivo a URL para mostrarselo al Cropper
    // Y abrimos el modal
    tempImageSrc.value = URL.createObjectURL(file);
    showCropModal.value = true;
    
    // Reseteamos el input para que pueda seleccionar el mismo archivo si se equivoca
    e.target.value = null; 
};

// 2. Cuando el usuario confirma el recorte
const cropImage = () => {
    const { canvas } = cropperRef.value.getResult();
    
    if (canvas) {
        // Redimensionamos a 400x400 para ahorrar espacio
        const maxSize = 400;
        let finalCanvas = canvas;
        
        if (canvas.width > maxSize || canvas.height > maxSize) {
            finalCanvas = document.createElement('canvas');
            const size = Math.min(canvas.width, canvas.height);
            finalCanvas.width = maxSize;
            finalCanvas.height = maxSize;
            
            const ctx = finalCanvas.getContext('2d');
            ctx.drawImage(canvas, 0, 0, size, size, 0, 0, maxSize, maxSize);
        }
        
        // Convertimos a JPEG con compresión (0.85 = 85% calidad) para reducir tamaño
        finalCanvas.toBlob((blob) => {
            // Creamos un archivo en JPEG (mucho más comprimido)
            const file = new File([blob], "avatar_cropped.jpg", { type: "image/jpeg" });
            
            // Guardamos en el formulario
            form.avatar = file;
            
            // Actualizamos la previsualización visual
            photoPreview.value = URL.createObjectURL(blob);
            
            // Cerramos modal
            showCropModal.value = false;
        }, 'image/jpeg', 0.85); // 85% de calidad para mejor compresión
    }
};

const selectNewPhoto = () => {
    photoInput.value.click();
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Información del Perfil
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Actualiza tu foto, nombre de usuario y biografía.
            </p>
        </header>

        <form @submit.prevent="form.post(route('profile.update'))" class="mt-6 space-y-6" enctype="multipart/form-data">
            
            <div>
                <InputLabel for="photo" value="Foto de Perfil" />

                <input
                    id="photo"
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    accept="image/*"
                    @change="onFileChange"
                >

                <div class="mt-2 flex items-center gap-4">
                    <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100 border-2 border-gray-200 dark:border-gray-700">
                        <div v-if="photoPreview" class="h-full w-full bg-cover bg-center"
                             :style="'background-image: url(\'' + photoPreview + '\');'">
                        </div>
                        <img v-else-if="user.avatar" :src="user.avatar" class="h-full w-full object-cover">
                        <div v-else class="h-full w-full flex items-center justify-center text-gray-400 text-2xl font-bold">
                            {{ user.name.charAt(0) }}
                        </div>
                    </div>

                    <SecondaryButton type="button" @click.prevent="selectNewPhoto">
                        Cambiar Foto
                    </SecondaryButton>
                </div>
                <InputError :message="form.errors.avatar" class="mt-2" />
            </div>

            <div>
                <InputLabel for="name" value="Nombre" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="username" value="Username" />
                <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required />
                <InputError class="mt-2" :message="form.errors.username" />
            </div>

            <div>
                <InputLabel for="bio" value="Biografía" />
                <textarea id="bio" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" v-model="form.bio" rows="3"></textarea>
                <InputError class="mt-2" :message="form.errors.bio" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Guardar Cambios</PrimaryButton>
                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Guardado.</p>
                </Transition>
            </div>
        </form>

        <div v-if="showCropModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/80 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-2xl max-w-lg w-full">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Ajusta tu foto</h3>
                
                <div class="overflow-hidden rounded-lg border border-gray-300 dark:border-gray-600 bg-black">
                    <Cropper
                        ref="cropperRef"
                        class="cropper"
                        :src="tempImageSrc"
                        :stencil-component="CircleStencil" 
                        :stencil-props="{ aspectRatio: 1/1 }"
                    />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="showCropModal = false">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton @click="cropImage">
                        Recortar y Usar
                    </PrimaryButton>
                </div>
            </div>
        </div>

    </section>
</template>

<style>
/* Estilo necesario para que el cropper tenga altura */
.cropper {
    height: 350px;
    background: #DDD;
}
</style>