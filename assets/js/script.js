document.addEventListener('DOMContentLoaded',()=>{
  const toggle=document.querySelector('.sidebar-toggle');
  const sidebar=document.querySelector('.sidebar');
  if(toggle&&sidebar)toggle.addEventListener('click',()=>sidebar.classList.toggle('open'));

  const passwordToggle=document.getElementById('togglePassword');
  const password=document.getElementById('password');
  if(passwordToggle&&password)passwordToggle.addEventListener('click',()=>{
    password.type=password.type==='password'?'text':'password';
    passwordToggle.innerHTML=password.type==='password'?'<i class="fa-regular fa-eye"></i>':'<i class="fa-regular fa-eye-slash"></i>';
  });

  if(window.Chart){
    const teal='#087d78';
    const statusCanvas=document.getElementById('statusChart');
    if(statusCanvas){
      const counts=JSON.parse(statusCanvas.dataset.counts||'[0,0,0]');
      new Chart(statusCanvas,{type:'doughnut',data:{labels:['Dalam Proses','Selesai','Ditolak'],datasets:[{data:counts,backgroundColor:[teal,'#52a98a','#df765f'],borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}}}});
    }
    const yearCanvas=document.getElementById('yearChart');
    if(yearCanvas){
      const chartData=JSON.parse(yearCanvas.dataset.chart||'{"labels":[],"values":[]}');
      new Chart(yearCanvas,{type:'bar',data:{labels:chartData.labels,datasets:[{label:'Permohonan',data:chartData.values,backgroundColor:teal,borderRadius:6}]},options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,grid:{color:'#edf1f0'},ticks:{precision:0}},x:{grid:{display:false}}}}});
    }
  }
});
