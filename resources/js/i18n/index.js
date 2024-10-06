import { createI18n } from 'vue-i18n';

import en from '../locales/en.json';
import kh from '../locales/kh.json';

export const i18n = createI18n({
    locale: 'en',
    fallbackLocale: 'en',
    messages: {en, kh}
});
