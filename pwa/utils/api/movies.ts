import { fetch } from '@/utils/dataAccess'
import { PagedCollection } from '@/types/collection'
import { Entity } from '@damianchojnacki/cinema'

export const getMoviesPath = (page?: string): string =>
  `/api/movies${(Number(page) > 0) ? `?page=${page}` : ''}`

export const getMovies = (page?: string) => fetch<PagedCollection<Entity.Movie>>(getMoviesPath(page))

export const getMoviePath = (movieId: string): string => `/api/movies/${movieId}`

export const getMovie = (id: string) => fetch<{ data: Entity.Movie }>(getMoviePath(id))
