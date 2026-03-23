import { useState, useCallback } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import { fetchAuditorias } from '../../data/api/auditoriasApi';

export const useAuditorias = () => {
  const [data, setData] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  const loadAuditorias = useCallback(async () => {
    try {
      setIsLoading(true);
      setError(null);
      // POR AHORA: Hardcodeamos un usuario responsabale ("uuid-user-1") hasta tener Auth
      const result = await fetchAuditorias("uuid-user-1");
      
      // Ordenar las pendientes primero
      const ordenado = result.sort((a, b) => {
        if (a.estado === "Pendiente" && b.estado !== "Pendiente") return -1;
        if (a.estado === "En Progreso" && b.estado === "Completada") return -1;
        return 0;
      });
      
      setData(ordenado);
    } catch (err) {
      setError(err.message || "No se pudieron cargar las auditorías");
    } finally {
      setIsLoading(false);
    }
  }, []);

  useFocusEffect(
    useCallback(() => {
      loadAuditorias();
    }, [loadAuditorias])
  );

  return {
    data,
    isLoading,
    error,
    refetch: loadAuditorias
  };
};
