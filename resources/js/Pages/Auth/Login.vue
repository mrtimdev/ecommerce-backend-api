<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { useMainStore } from '@/stores/main'
const mainStore = useMainStore()

onMounted(() => {
    mainStore.initParticle()
})
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    login_logo: {
        type: String,
        required: false,
    }
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style lang="scss" scoped>
    .particles_background {
        display: block;
        left: 0;
        position: absolute;
        top: 0;
        z-index: -1;
        height: 100vh !important;
    }
</style>

<template>
    <GuestLayout :login_logo="login_logo">
        <Head title="Log in" />
        <canvas class="particles_background"></canvas>
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <div class="relative">
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                    <span class="absolute right-4 top-2">
                        <i class="fa fa-user"></i>
                    </span>
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <div class="relative">
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                    <span class="absolute right-4 top-2">
                        <i class="fa fa-lock"></i>
                    </span>
                </div>
            </div>

            <div class="block mt-4">
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </div>


                </div>
            </div>

            <div class="flex items-center mt-4 justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
