<script setup lang="ts">
import { ref } from 'vue';
import { Form, Head } from '@inertiajs/vue3';
import { ArrowRight, Eye, EyeOff } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';

const showPassword = ref(false);
const showConfirmation = ref(false);

defineProps<{ passwordRules: string }>();

defineOptions({
    layout: {
        title: 'Mulai belajar.',
        description: 'Buat akun untuk menemukan kursus sesuai kebutuhan Anda.',
    },
});
</script>

<template>
    <Head title="Daftar" />

    <a class="google-btn" href="/auth/google/redirect">
        <svg
            viewBox="0 0 48 48"
            width="19"
            height="19"
            aria-hidden="true"
        >
            <path fill="#EA4335" d="M24 9.5c3.5 0 6.7 1.2 9.2 3.6l6.9-6.9C35.9 2.4 30.4 0 24 0 14.6 5.4 6.5 13.2l8 6.2C12.5 13.5 17.9 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.9 24.6c0-1.6-.2-3.2-.5-4.6H24v9.1h12.8c-.6 3-2.3 5.5-4.9 7.2l7.5 5.8C43.8 38 46.9 32.1 46.9 24.6z"/>
            <path fill="#FBBC05" d="M10.5 28.6c-.5-1.5-.8-3-.8-4.6s.3-3.1.8-4.6l-8-6.2C.9 16.5 0 20.1 0 24s.9 7.5 2.5 10.8l8-6.2z"/>
            <path fill="#34A853" d="M24 48c6.5 0 11.9-2.1 15.8-5.9l-7.5-5.8c-2.1 1.4-4.8 2.2-8.3 2.2-6.1 0-11.5-4-13.5-9.5l-8 6.2C6.5 42.6 14.6 48 24 48z"/>
        </svg>
        Daftar dengan Google
    </a>

    <div class="divider" aria-hidden="true">
        <span>ATAU GUNAKAN EMAIL</span>
    </div>

    <Form
        v-bind="store.form()"
        :reset-on-success="['password', 'password_confirmation']"
        v-slot="{ errors, processing }"
        class="form-stack"
    >
        <div class="form-field">
            <label for="name">Nama lengkap</label>
            <input
                id="name"
                name="name"
                v-focus
                type="text"
                autocomplete="name"
                required
                placeholder="Nama Anda"
            />
            <InputError :message="errors.name" />
        </div>

        <div class="form-field">
            <label for="email">Email</label>
            <input
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                placeholder="nama@email.com"
            />
            <InputError :message="errors.email" />
        </div>

        <div class="form-field">
            <label for="password">Kata sandi</label>
            <div class="password-input">
                <input
                    id="password"
                    name="password"
                    :type="showPassword ? 'text' : 'password'"
                    autocomplete="new-password"
                    required
                    placeholder="Buat kata sandi"
                />
                <button
                    class="peek"
                    type="button"
                    :aria-label="showPassword
                        ? 'Sembunyikan kata sandi'
                        : 'Tampilkan kata sandi'"
                    :aria-pressed="showPassword"
                    @click="showPassword = !showPassword"
                >
                    <EyeOff v-if="showPassword" :size="18" />
                    <Eye v-else :size="18" />
                </button>
            </div>
            <InputError :message="errors.password" />
        </div>

        <div class="form-field">
            <label for="password_confirmation">
                Konfirmasi kata sandi
            </label>
            <div class="password-input">
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    :type="showConfirmation ? 'text' : 'password'"
                    autocomplete="new-password"
                    required
                    placeholder="Ulangi kata sandi"
                />
                <button
                    class="peek"
                    type="button"
                    :aria-label="showConfirmation
                        ? 'Sembunyikan kata sandi'
                        : 'Tampilkan kata sandi'"
                    :aria-pressed="showConfirmation"
                    @click="showConfirmation = !showConfirmation"
                >
                    <EyeOff v-if="showConfirmation" :size="18" />
                    <Eye v-else :size="18" />
                </button>
            </div>
            <InputError :message="errors.password_confirmation" />
        </div>

        <button
            class="submit-btn"
            type="submit"
            :disabled="processing"
            data-test="register-user-button"
        >
            <span>{{ processing ? 'Memproses...' : 'Buat akun' }}</span>
            <ArrowRight :size="18" aria-hidden="true" />
        </button>
    </Form>

    <p class="switch-auth">
        Sudah punya akun?
        <a :href="login().url">
            Masuk <span aria-hidden="true">↗</span>
        </a>
    </p>
</template>

<style scoped>
.google-btn {
    display: flex;
    gap: 12px;
    justify-content: center;
    align-items: center;
    width: 100%;
    min-height: 49px;
    border: 1px solid #AAA399;
    background: white;
    color: #171716;
    font: 600 14px 'IBM Plex Sans', Arial, sans-serif;
    text-decoration: none;
}

.google-btn:hover {
    border-color: #171716;
    background: #FDFCF9;
}

.divider {
    display: flex;
    align-items: center;
    gap: 14px;
    margin: 21px 0 22px;
    color: #756F66;
    font: 600 10px 'IBM Plex Sans', Arial, sans-serif;
    letter-spacing: .1em;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #C9C4BA;
}

.form-stack {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
}

.form-field label {
    font-size: 13px;
    font-weight: 600;
    color: #171716;
}

.form-field input {
    width: 100%;
    height: 47px;
    border: 1px solid #BFBAB1;
    border-radius: 0;
    outline: 0;
    background: #FFFEFC;
    color: #171716;
    padding: 0 14px;
    font: 400 15px 'IBM Plex Sans', Arial, sans-serif;
}

.form-field input:focus {
    border-color: #171716;
    box-shadow: inset 0 0 0 1px #171716;
}

.form-field input::placeholder {
    color: #847D72;
}

.password-input {
    position: relative;
}

.password-input input {
    padding-right: 52px;
}

.peek {
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
    color: #655F57;
    cursor: pointer;
}

.submit-btn {
    margin-top: 3px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    width: 100%;
    min-height: 50px;
    padding: 0 19px;
    border: 1px solid #171716;
    background: #171716;
    color: white;
    font: 600 14px 'IBM Plex Sans', Arial, sans-serif;
    cursor: pointer;
}

.submit-btn:hover:not(:disabled) {
    background: #D94A2F;
    border-color: #D94A2F;
}

.submit-btn:disabled {
    opacity: .65;
    cursor: progress;
}

.switch-auth {
    margin: 22px 0 0;
    text-align: center;
    color: #655F57;
    font-size: 13px;
}

.switch-auth a {
    color: #9A321F;
    font-weight: 600;
    text-decoration: none;
    margin-left: 5px;
}

.switch-auth a:hover {
    text-decoration: underline;
    text-underline-offset: 4px;
}
</style>
