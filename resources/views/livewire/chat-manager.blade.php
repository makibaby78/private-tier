<button 
    x-data 
    @click="$wire.$dispatch('send-message', { id: 123 })"
>
    Send Event to Livewire
</button>
