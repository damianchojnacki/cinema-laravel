import {
  GetServerSideProps,
  NextComponentType,
} from 'next'
import DefaultErrorPage from 'next/error'
import Head from 'next/head'
import { useRouter } from 'next/router'
import { dehydrate, QueryClient, useQuery } from '@tanstack/react-query'
import { getShowing, getShowingPath } from '@/utils/api/showings'
import { Reservation } from '@damianchojnacki/cinema'
import { getMovie, getMoviePath } from '@/utils/api/movies'
import Layout from '@/components/Layout'

const Page: NextComponentType = () => {
  const { query: { id, showingId } } = useRouter()

  const { data: { data: movie } = {} } = useQuery({
    queryKey: [getMoviePath(id as string)],
    queryFn: async () => await getMovie(id as string),
  })

  const { data: { data: showing } = {} } = useQuery({
    queryKey: [getShowingPath(id as string, showingId as string)],
    queryFn: async () => await getShowing(id as string, showingId as string),
  })

  if ((showing == null) || (movie == null)) {
    return <DefaultErrorPage statusCode={404} />
  }

  return (
    <Layout>
      <Head>
        <title>{`Reservation - ${movie.title}`}</title>
      </Head>

      <Reservation.Create showing={showing} movie={movie} />
    </Layout>
  )
}

export const getServerSideProps: GetServerSideProps = async ({ params }) => {
  if (!params?.id || !params?.showingId || Array.isArray(params?.id) || Array.isArray(params?.showingId)) {
    return {
      notFound: true,
    }
  }

  const queryClient = new QueryClient()

  await queryClient.prefetchQuery({
    queryKey: [getShowingPath(params.id, params.showingId)],
    queryFn: async () => await getShowing(params.id as string, params.showingId as string),
  })

  await queryClient.prefetchQuery({
    queryKey: [getMoviePath(params.id)],
    queryFn: async () => await getMovie(params.id as string),
  })

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
    },
  }
}

export default Page
