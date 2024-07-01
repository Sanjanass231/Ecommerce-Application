import './bootstrap';
import 'preline';
// this line will autoinitialize javascript preine code when the livewire wire:navigate navigated
document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoInit();
})