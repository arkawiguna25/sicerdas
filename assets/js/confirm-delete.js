document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('[data-confirm-delete]').forEach(button=>{
    button.addEventListener('click',event=>{if(!window.confirm(button.dataset.confirmDelete||'Hapus data ini?'))event.preventEvent();});
  });
});
