<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { onBeforeUnmount, onMounted, ref } from "vue";
import { useMainStore } from "@/stores/main";
const mainStore = useMainStore();

onMounted(() => {
  // mainStore.initParticle()
});
const props = defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
  login_logo: {
    type: String,
    required: false,
  },
});

const form = useForm({
  identify: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
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
    <!-- <canvas class="particles_background"></canvas> -->
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <div class="grid items-center justify-center pb-[25px]">
      <Link :href="route('dashboard')">
        <img :src="`${login_logo}`" class="w-20 h-20 fill-current text-gray-500" />
      </Link>
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="identify" class="!text-gray-700" value="Username/Email" />
        <div class="relative">
          <TextInput
            id="identify"
            type="identify"
            class="mt-1 block w-full !bg-white !text-gray-700"
            v-model="form.identify"
            required
            autofocus
            autocomplete="username"
          />

          <InputError class="mt-2" :message="form.errors.identify" />
          <span class="absolute right-4 top-2">
            <i class="fa fa-user"></i>
          </span>
        </div>
      </div>

      <div class="mt-4">
        <InputLabel for="password" class="!text-gray-700" value="Password" />
        <div class="relative">
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full !bg-white !text-gray-700"
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

      <div class="flex items-center mt-4 justify-between">
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
            <label for="remember" class="ms-2 text-sm text-gray-600">Remember me</label>
          </div>
        </div>
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Login
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
