import { fetch } from '@/utils/dataAccess'
import { Entity, CreateReservationPayload } from '@damianchojnacki/cinema'

export const getCreateReservationPath = (showingId: string): string => `/api/showings/${showingId}/reservations`

export const createReservation = (showingId: string, data: CreateReservationPayload) =>
  fetch<Entity.Reservation, CreateReservationPayload>(getCreateReservationPath(showingId), 'POST', data)
