import React, { useMemo } from 'react'
import Authenticated from '@/components/layouts/Authenticated'
import { Head } from '@inertiajs/inertia-react'
import { DefaultProps, PaginatedInterface } from '@/api/types/default'
import Main from '@/components/Main'
import { Button, Paper, Table } from '@mantine/core'
import { Node } from '@/api/admin/nodes/types'
import EmptyState from '@/components/EmptyState'
import { ServerIcon } from '@heroicons/react/outline'
import { Inertia } from '@inertiajs/inertia'
import RoundedButton from '@/components/RoundedButton'
import { PencilIcon } from '@heroicons/react/solid'
import EditButton from '@/components/elements/tables/EditButton'
import Paginator from '@/components/elements/pagination/Paginator'

interface Props extends DefaultProps {
  nodes: PaginatedInterface<Node[]>
}

export default function Index({ auth, nodes }: Props) {
  return (
    <Authenticated auth={auth} header={<h1 className='h1'>Nodes</h1>}>
      <Head title='Nodes' />

      <Main>
        <h2 className='h3-deemphasized'>Nodes</h2>
        <Paper shadow='xs' className='p-card w-full'>
          <div className='flex justify-end'>
            <Button onClick={() => Inertia.visit(route('admin.nodes.create'))}>
              Import Node
            </Button>
          </div>

          <div className='overflow-auto'>
            <Table className='mt-3' striped highlightOnHover>
              <thead>
                <tr>
                  <th>Display Name</th>
                  <th>Cluster</th>
                  <th>Address</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                {nodes.data.map((node) => (
                  <tr key={node.id}>
                    <td>{node.name}</td>
                    <td>{node.cluster}</td>
                    <td>{`${node.hostname}:${node.port}`}</td>
                    <td>
                      <EditButton
                        onClick={() =>
                          Inertia.visit(route('admin.nodes.show', node.id))
                        }
                      />
                    </td>
                  </tr>
                ))}
              </tbody>
            </Table>
          </div>

          <Paginator pages={nodes.meta.pagination.total_pages} route='admin.nodes' />

          {nodes.meta.pagination.total === 0 && (
            <EmptyState
              icon={ServerIcon}
              title='No Nodes'
              description='Get started by importing a new node.'
              action='Import Node'
              onClick={() => Inertia.visit(route('admin.nodes.create'))}
            />
          )}
        </Paper>
      </Main>
    </Authenticated>
  )
}
