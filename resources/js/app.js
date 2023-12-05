import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';

window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.plugin(collapse);
Alpine.plugin(persist);

Alpine.start();
