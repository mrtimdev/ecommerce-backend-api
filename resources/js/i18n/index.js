import { createI18n } from 'vue-i18n';

import en from '../../../lang/en.json';
import kh from '../../../lang/kh.json';

export const i18n = createI18n({
    locale: 'en',
    fallbackLocale: 'en',
    messages: {en, kh}
});
