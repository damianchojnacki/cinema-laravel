import { fetch } from '@/utils/dataAccess'
import { Entity } from '@damianchojnacki/cinema'
import { Collection } from '@/types/collection'

export const getShowingsPath = (movieId: string): string => `/api/movies/${movieId}/showings`

export const getShowings = (movieId: string) => async () =>
  await fetch<Collection<Entity.Showing>>(getShowingsPath(movieId))

export const getShowingPath = (movieId: string, id: string): string => `/api/movies/${movieId}/showings/${id}`

export const getShowing = (movieId: string, id: string) => fetch<{ data: Entity.Showing }>(getShowingPath(movieId, id))
