import '../styles/globals.css'
import '@damianchojnacki/cinema/style.css'
import type { AppProps } from 'next/app'
import type { DehydratedState } from '@tanstack/react-query'
import { routes } from '@/utils/routes'
import { client } from '@/utils/api/client'
import QueryClientProvider from '@/components/QueryClientProvider'
import { CinemaContextProvider } from '@damianchojnacki/cinema'
import Link from 'next/link'

function MyApp({ Component, pageProps }: AppProps<{ dehydratedState: DehydratedState }>) {
  return (
    <QueryClientProvider dehydratedState={pageProps.dehydratedState}>
      <CinemaContextProvider routes={routes} apiClient={client} link={Link}>
        <Component {...pageProps} />
      </CinemaContextProvider>
    </QueryClientProvider>
  )
}

export default MyApp
