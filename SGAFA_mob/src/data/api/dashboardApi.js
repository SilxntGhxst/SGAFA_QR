import { apiClient } from './apiClient';

const mapBuzonToActivity = (item) => {
  let tipoBadge = "info";
  const tipoStr = (item.tipo || "").toLowerCase();
  
  if (tipoStr.includes("daño") || tipoStr.includes("dañado")) {
    tipoBadge = "alerta";
  } else if (tipoStr.includes("faltante") || tipoStr.includes("sin registro")) {
    tipoBadge = "aviso";
  } else {
    tipoBadge = "exito";
  }

  // Formato de fecha legible (DD/MM HH:MM)
  const d = new Date(item.created_at || Date.now());
  const dia = String(d.getDate()).padStart(2, '0');
  const mes = String(d.getMonth() + 1).padStart(2, '0');
  const hora = String(d.getHours()).padStart(2, '0');
  const min = String(d.getMinutes()).padStart(2, '0');
  const fmtDate = `${dia}/${mes}, ${hora}:${min}`;

  return {
    id: String(item.id),
    tipo: tipoBadge,
    titulo: item.activo || item.tipo,
    fecha: fmtDate,
    estado: item.estado || "Pendiente"
  };
};

export const fetchDashboardData = async () => {
  try {
    const [statsRes, buzonRes] = await Promise.all([
      apiClient('/activos/stats'),
      apiClient('/buzon')
    ]);
    
    // Ordenar para mostrar los más recientes primero
    const buzonList = (buzonRes.data || []).sort((a, b) => b.id - a.id);
    const mappedActivities = buzonList.map(mapBuzonToActivity);
    
    return {
      usuario: { nombre: "Santiago", rol: "Resguardante / Auditor" },
      metricas: { 
        auditoriasPendientes: statsRes.solicitudes_pendientes || 0, 
        activosAsignados: statsRes.total_activos || 0 
      },
      actividadReciente: mappedActivities.slice(0, 3), // En la vista principal solo 3
      historial: mappedActivities // En el modal todos
    };
  } catch (error) {
    console.error("Dashboard fetch error:", error);
    throw error;
  }
};
