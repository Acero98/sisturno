document.addEventListener('DOMContentLoaded', () => {
    const data = window.reporteGeneralData || {};
    const crear = (selector, opciones) => { const nodo = document.querySelector(selector); if (nodo && window.ApexCharts) new ApexCharts(nodo, opciones).render(); };
    const etiquetas = (grupo) => (data[grupo] || []).map(x => x.etiqueta);
    const valores = (grupo) => (data[grupo] || []).map(x => Number(x.total || 0));
    crear('#graficoEstados', { chart:{type:'donut',height:300}, labels:etiquetas('estados'), series:valores('estados'), legend:{position:'bottom'} });
    crear('#graficoServicios', { chart:{type:'bar',height:300,toolbar:{show:false}}, series:[{name:'Atenciones',data:valores('servicios')}], xaxis:{categories:etiquetas('servicios')}, plotOptions:{bar:{horizontal:true}}, dataLabels:{enabled:true} });
    crear('#graficoTendencia', { chart:{type:'line',height:300,toolbar:{show:false}}, series:[{name:'Tickets',data:valores('tendencia')}], xaxis:{categories:etiquetas('tendencia')}, stroke:{curve:'smooth',width:3}, dataLabels:{enabled:true} });
    crear('#graficoHorasPico', { chart:{type:'bar',height:300,toolbar:{show:false}}, series:[{name:'Tickets',data:valores('horasPico')}], xaxis:{categories:etiquetas('horasPico')}, dataLabels:{enabled:true} });
});
