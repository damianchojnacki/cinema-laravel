import { createReservation } from '@/utils/api/reservations'
import { Client } from '@damianchojnacki/cinema'

export const client: Client = {
  createReservation: (showingId, data) => createReservation(showingId, data),
}
