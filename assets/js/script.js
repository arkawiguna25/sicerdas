document.addEventListener('DOMContentLoaded',()=>{
  const toggle=document.querySelector('.sidebar-toggle');
  const sidebar=document.querySelector('.sidebar');
  const overlay=document.querySelector('.sidebar-overlay');
  const closeMenu=()=>{sidebar?.classList.remove('open');overlay?.classList.remove('show');toggle?.setAttribute('aria-expanded','false');};
  if(toggle&&sidebar){toggle.addEventListener('click',()=>{const open=sidebar.classList.toggle('open');overlay?.classList.toggle('show',open);toggle.setAttribute('aria-expanded',String(open));});}
  overlay?.addEventListener('click',closeMenu);
  document.addEventListener('keydown',event=>{if(event.key==='Escape')closeMenu();});

  const passwordToggle=document.getElementById('togglePassword');
  const password=document.getElementById('password');
  if(passwordToggle&&password)passwordToggle.addEventListener('click',()=>{
    const visible=password.type==='password';password.type=visible?'text':'password';
    passwordToggle.innerHTML=visible?'<i class="fa-regular fa-eye-slash"></i>':'<i class="fa-regular fa-eye"></i>';
    passwordToggle.setAttribute('aria-label',visible?'Sembunyikan password':'Tampilkan password');
  });

  if(window.Chart){
    const teal='#28634d';const statusCanvas=document.getElementById('statusChart');
    if(statusCanvas){const counts=JSON.parse(statusCanvas.dataset.counts||'[0,0,0]');new Chart(statusCanvas,{type:'doughnut',data:{labels:['Dalam proses','Selesai / disetujui','Ditolak'],datasets:[{data:counts,backgroundColor:['#d9aa58','#75a77b','#bd7164'],borderWidth:0,hoverOffset:5}]},options:{responsive:true,maintainAspectRatio:false,cutout:'68%',plugins:{legend:{position:'bottom',labels:{usePointStyle:true,boxWidth:7,padding:17,font:{size:10}}}}}});}
    const yearCanvas=document.getElementById('yearChart');
    if(yearCanvas){const chartData=JSON.parse(yearCanvas.dataset.chart||'{"labels":[],"values":[]}');new Chart(yearCanvas,{type:'bar',data:{labels:chartData.labels,datasets:[{label:'Permohonan',data:chartData.values,backgroundColor:'#75a77b',hoverBackgroundColor:teal,borderRadius:7,maxBarThickness:34}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#edf0e9'},ticks:{precision:0}},x:{grid:{display:false}}}}});}
  }
});
