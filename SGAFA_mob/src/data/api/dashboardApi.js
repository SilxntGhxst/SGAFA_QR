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

  const d = new Date(item.created_at || Date.now());
  const dia  = String(d.getDate()).padStart(2, '0');
  const mes  = String(d.getMonth() + 1).padStart(2, '0');
  const hora = String(d.getHours()).padStart(2, '0');
  const min  = String(d.getMinutes()).padStart(2, '0');
  const fmtDate = `${dia}/${mes}, ${hora}:${min}`;

  return {
    id:     String(item.id),
    tipo:   tipoBadge,
    titulo: item.activo || item.tipo,
    fecha:  fmtDate,
    estado: item.estado || "Pendiente"
  };
};

/**
 * Fetch dashboard data.
 * @param {object|null} authUser - user object from AuthContext
 */
export const fetchDashboardData = async (authUser = null) => {
  try {
    // Peticiones en paralelo: buzón + estadísticas del usuario
    const requests = [apiClient('/buzon')];
    if (authUser?.id) {
      // Activos asignados al usuario
      requests.push(apiClient(`/activos/?usuario_id=${authUser.id}`));
      // Auditorías en estado Pendiente o En Progreso asignadas al usuario
      requests.push(apiClient(`/auditorias/?usuario_id=${authUser.id}&estado=Pendiente`));
      requests.push(apiClient(`/auditorias/?usuario_id=${authUser.id}&estado=En Progreso`));
    }

    const [buzonRes, activosRes, audPendientesRes, audEnProgresoRes] = await Promise.all(requests);

    const buzonList = (buzonRes.data || []).sort((a, b) => b.id - a.id);
    const mappedActivities = buzonList.map(mapBuzonToActivity);

    const activosAsignados    = activosRes?.total ?? 0;
    const auditoriasPendientes =
      (audPendientesRes?.total ?? 0) + (audEnProgresoRes?.total ?? 0);

    return {
      usuario: authUser
        ? { nombre: authUser.nombre, rol: authUser.rol || 'Resguardante' }
        : { nombre: 'Usuario', rol: '' },
      metricas: {
        // Solo auditorías activas (Pendiente + En Progreso) del usuario
        auditoriasPendientes,
        // Activos asignados al usuario
        activosAsignados,
      },
      actividadReciente: mappedActivities.slice(0, 3),
      historial:         mappedActivities
    };
  } catch (error) {
    console.error("Dashboard fetch error:", error);
    throw error;
  }
};
