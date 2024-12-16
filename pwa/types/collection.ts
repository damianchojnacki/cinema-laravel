export interface Collection<T> {
  data: T[]
}

export interface PagedCollection<T> {
  data: T[]
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    from: number
    last_page: number
    links: {
      url: string | null
      label: string
      active: boolean
    }[]
    path: string
    per_page: number
    to: number
    total: number
  }
}

export const isPagedCollection = <T>(data: unknown): data is PagedCollection<T> => {
  if (typeof data !== 'object' || data === null) {
    return false
  }

  const typedData = data as Partial<PagedCollection<T>>

  return 'meta' in typedData && Array.isArray(typedData.meta)
}
