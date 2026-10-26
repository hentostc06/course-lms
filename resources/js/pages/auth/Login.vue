<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head } from '@inertiajs/vue3';
import { ArrowRight, Eye, EyeOff } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import PasskeyVerify from '@/components/PasskeyVerify.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

const showPassword = ref(false);

defineOptions({
    layout: {
        title: 'Masuk ke akun.',
        description: 'Lanjutkan proses belajar dari tempat terakhir Anda.',
    },
});

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();
</script>

<template>
    <Head title="Masuk" />

    <p v-if="status" class="status-message" role="status">
        {{ status }}
    </p>

    <div class="social-login">
        <a class="google-btn" href="/auth/google/redirect">
            <svg viewBox="0 0 48 48" width="19" height="19" aria-hidden="true">
                <path
                    fill="#EA4335"
                    d="M24 9.5c3.5 0 6.7 1.2 9.2 3.6l6.9-6.9C35.9 2.4 30.4 0 24 0 14.6 5.4 6.5 13.2l8 6.2C12.5 13.5 17.9 9.5 24 9.5z"
                />
                <path
                    fill="#4285F4"
                    d="M46.9 24.6c0-1.6-.2-3.2-.5-4.6H24v9.1h12.8c-.6 3-2.3 5.5-4.9 7.2l7.5 5.8C43.8 38 46.9 32.1 46.9 24.6z"
                />
                <path
                    fill="#FBBC05"
                    d="M10.5 28.6c-.5-1.5-.8-3-.8-4.6s.3-3.1.8-4.6l-8-6.2C.9 16.5 0 20.1 0 24s.9 7.5 2.5 10.8l8-6.2z"
                />
                <path
                    fill="#34A853"
                    d="M24 48c6.5 0 11.9-2.1 15.8-5.9l-7.5-5.8c-2.1 1.4-4.8 2.2-8.3 2.2-6.1 0-11.5-4-13.5-9.5l-8 6.2C6.5 42.6 14.6 48 24 48z"
                />
            </svg>
            <span>Masuk dengan Google</span>
        </a>

        <div class="passkey-area">
            <PasskeyVerify
                :hide-separator="true"
                label="Masuk dengan passkey"
            />
        </div>
    </div>

    <div class="divider" aria-hidden="true">
        <span>ATAU DENGAN EMAIL</span>
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password']"
        v-slot="{ errors, processing }"
        class="form-stack"
    >
        <div class="form-field">
            <label for="email">Email</label>
            <input
                id="email"
                v-focus
                type="email"
                name="email"
                autocomplete="email"
                required
                placeholder="nama@email.com"
            />
            <InputError :message="errors.email" />
        </div>

        <div class="form-field">
            <div class="form-label-row">
                <label for="password">Kata sandi</label>
                <a
                    v-if="canResetPassword"
                    :href="request().url"
                    class="text-link"
                >
                    Lupa kata sandi?
                </a>
            </div>

            <div class="password-input">
                <input
                    id="password"
                    name="password"
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="current-password"
                    required
                    placeholder="Masukkan kata sandi"
                />

                <button
                    type="button"
                    class="peek"
                    :aria-label="
                        showPassword
                            ? 'Sembunyikan kata sandi'
                            : 'Tampilkan kata sandi'
                    "
                    :aria-pressed="showPassword"
                    @click="showPassword = !showPassword"
                >
                    <EyeOff v-if="showPassword" :size="18" aria-hidden="true" />
                    <Eye v-else :size="18" aria-hidden="true" />
                </button>
            </div>
            <InputError :message="errors.password" />
        </div>

        <label class="remember">
            <input type="checkbox" name="remember" value="1" />
            <span>Ingat saya</span>
        </label>

        <button
            class="submit-btn"
            type="submit"
            :disabled="processing"
            data-test="login-button"
        >
            <span>
                {{ processing ? 'Memproses...' : 'Masuk ke akun' }}
            </span>
            <ArrowRight :size="18" aria-hidden="true" />
        </button>
    </Form>

    <p class="switch-auth">
        Belum punya akun?
        <a :href="register().url">
            Daftar sekarang <span aria-hidden="true">↗</span>
        </a>
    </p>
</template>

<style scoped>
.social-login {
    display: grid;
    gap: 7px;
}

.google-btn {
    display: flex;
    gap: 12px;
    align-items: center;
    justify-content: center;
    width: 100%;
    min-height: 49px;
    background: #fff;
    border: 1px solid #aaa399;
    color: #171716;
    font:
        600 14px 'IBM Plex Sans',
        Arial,
        sans-serif;
    text-decoration: none;
    transition:
        border-color 0.15s,
        background 0.15s;
}

.google-btn:hover {
    border-color: #171716;
    background: #fdfcf9;
}

.google-btn svg {
    flex-shrink: 0;
}

.passkey-area :deep(button) {
    width: 100%;
    min-height: 43px;
    border: 0 !important;
    border-radius: 0 !important;
    background: transparent !important;
    color: #655f57 !important;
    box-shadow: none !important;
    font-family: 'IBM Plex Sans', Arial, sans-serif !important;
    font-size: 13px !important;
}

.passkey-area :deep(button:hover) {
    background: #e9e4dc !important;
    color: #171716 !important;
}

.divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 19px 0 22px;
    color: #756f66;
    font:
        600 10px/1.4 'IBM Plex Sans',
        Arial,
        sans-serif;
    letter-spacing: 0.1em;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #c9c4ba;
}

.divider span {
    white-space: nowrap;
}

.form-stack {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-field label {
    color: #171716;
    font-size: 13px;
    font-weight: 600;
}

.form-label-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
}

.form-field input {
    width: 100%;
    height: 48px;
    border: 1px solid #bfbab1;
    border-radius: 0;
    padding: 0 14px;
    outline: 0;
    background: #fffefc;
    color: #171716;
    font:
        400 15px 'IBM Plex Sans',
        Arial,
        sans-serif;
    transition: border-color 0.15s;
}

.form-field input::placeholder {
    color: #847d72;
}

.form-field input:focus {
    border-color: #171716;
    box-shadow: inset 0 0 0 1px #171716;
}

.password-input {
    position: relative;
}

.password-input input {
    padding-right: 52px;
}

.password-input .peek {
    position: absolute;
    right: 1px;
    top: 1px;
    bottom: 1px;
    width: 47px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 0;
    background: transparent;
    color: #655f57;
    cursor: pointer;
}

.password-input .peek:hover {
    color: #171716;
}

.remember {
    display: flex;
    align-items: center;
    gap: 9px;
    cursor: pointer;
    color: #544e46;
    font-size: 13px;
    width: fit-content;
}

.remember input {
    width: 16px;
    height: 16px;
    accent-color: #171716;
}

.submit-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 0 19px;
    width: 100%;
    min-height: 50px;
    background: #171716;
    color: #fff;
    border: 1px solid #171716;
    cursor: pointer;
    font:
        600 14px 'IBM Plex Sans',
        Arial,
        sans-serif;
    transition: background 0.15s;
}

.submit-btn:hover:not(:disabled) {
    background: #d94a2f;
    border-color: #d94a2f;
}

.submit-btn:disabled {
    opacity: 0.65;
    cursor: progress;
}

.text-link,
.switch-auth a {
    font-size: 13px;
    color: #9a321f;
    font-weight: 600;
    text-decoration: none;
}

.text-link:hover,
.switch-auth a:hover {
    text-decoration: underline;
    text-underline-offset: 4px;
}

.switch-auth {
    margin: 22px 0 0;
    text-align: center;
    color: #655f57;
    font-size: 13px;
}

.switch-auth a {
    margin-left: 5px;
}

.status-message {
    border-left: 3px solid #d94a2f;
    padding: 11px 14px;
    margin-bottom: 20px;
    background: #ece5dc;
    color: #332820;
    font-size: 13px;
}

@media (prefers-reduced-motion: reduce) {
    .google-btn,
    .submit-btn,
    .form-field input {
        transition: none;
    }
}
</style>
