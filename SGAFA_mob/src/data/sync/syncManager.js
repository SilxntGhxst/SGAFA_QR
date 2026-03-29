import AsyncStorage from '@react-native-async-storage/async-storage';

const SYNC_QUEUE_KEY = '@sgafa_sync_queue';

export const SyncManager = {
  /**
   * Añade una operación a la cola de sincronización
   */
  async addToQueue(endpoint, method, data) {
    try {
      const queueStr = await AsyncStorage.getItem(SYNC_QUEUE_KEY);
      const queue = queueStr ? JSON.parse(queueStr) : [];
      
      const newItem = {
        id: Date.now(),
        endpoint,
        method,
        data,
        timestamp: new Date().toISOString()
      };
      
      queue.push(newItem);
      await AsyncStorage.setItem(SYNC_QUEUE_KEY, JSON.stringify(queue));
      console.log('Operación añadida a la cola de sincronización:', endpoint);
    } catch (e) {
      console.error('Error al guardar en cola:', e);
    }
  },

  /**
   * Obtiene todos los items pendientes
   */
  async getQueue() {
    const queueStr = await AsyncStorage.getItem(SYNC_QUEUE_KEY);
    return queueStr ? JSON.parse(queueStr) : [];
  },

  /**
   * Borra un item de la cola
   */
  async removeFromQueue(id) {
    const queue = await this.getQueue();
    const updated = queue.filter(item => item.id !== id);
    await AsyncStorage.setItem(SYNC_QUEUE_KEY, JSON.stringify(updated));
  },

  /**
   * Limpia toda la cola
   */
  async clearQueue() {
    await AsyncStorage.removeItem(SYNC_QUEUE_KEY);
  }
};
