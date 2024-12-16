import { ENTRYPOINT } from '@/config/entrypoint'
import { ApiError } from '@damianchojnacki/cinema'
import axios, { AxiosError } from 'axios'
import { Item } from '@/types/item'
import { Collection, isPagedCollection, PagedCollection } from '@/types/collection'

const MIME_TYPE = 'application/json'

export interface LaravelError {
  message: string
  errors: Record<string, string[]>
}

export const fetch = async <T, K = object>(
  path: string,
  method = 'GET',
  data?: K,
): Promise<T> => {
  try {
    const response = await axios<T>({
      url: ENTRYPOINT + path,
      method,
      data,
      headers: {
        'Accept': MIME_TYPE,
        'Content-Type': MIME_TYPE,
      },
    })

    return response.data
  } catch (e) {
    if (
      !axios.isAxiosError(e) ||
      !(e as AxiosError<LaravelError>).response?.data?.message ||
      !(e as AxiosError<LaravelError>).response?.data?.errors
    ) {
      // fix bug - TypeError: Converting circular structure to JSON
      throw JSON.parse(JSON.stringify(e))
    }

    const data: LaravelError = e.response?.data as LaravelError

    const errors: Record<string, string> = {}

    Object.entries(data.errors).map(([key, value]) => {
      errors[key] = value[0]
    })

    throw new ApiError(data.message, {
      data: {
        message: data.message,
        errors,
      },
    })
  }
}

export const getItemIds = async <T extends Item>(
  response: PagedCollection<T> | Collection<T>,
) => {
  if (response == null) return []

  if (!isPagedCollection(response)) {
    return response.data.map(({ id }) => id)
  }

  try {
    const ids = response.data.map(({ id }) => id)

    for (let page = 2; page <= response.meta.last_page; page++) {
      console.log(page)
      ids.push(
        ...((
          await fetch<PagedCollection<T>>(`${response.meta.path}?page=${page}`)
        )?.data?.map(({ id }) => id) ?? []),
      )
    }

    return ids
  } catch (e) {
    console.error(e)

    return []
  }
}
