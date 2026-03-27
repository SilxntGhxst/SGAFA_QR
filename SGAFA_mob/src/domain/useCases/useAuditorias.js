import { useState, useCallback } from 'react';
import { useFocusEffect } from '@react-navigation/native';
import { fetchAuditorias } from '../../data/api/auditoriasApi';
import { useAuth } from '../AuthContext';

export const useAuditorias = () => {
  const { user } = useAuth();
  const [data, setData] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);

  const loadAuditorias = useCallback(async () => {
    if (!user?.id) {
      setIsLoading(false);
      return;
    }
    
    try {
      setIsLoading(true);
      setError(null);
      const result = await fetchAuditorias(user.id);
      
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
  }, [user?.id]);

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
