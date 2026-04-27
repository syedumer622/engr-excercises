import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { products } from '@/routes';

interface Product {
    id: number;
    title: string;
    description: string;
    price: string;
    sku: string | null;
    shopify_id: string | null;
    created_at: string;
    updated_at: string;
}

interface Pagination {
    data: Product[];
    current_page: number;
    last_page: number;
    prev_page_url: string | null;
    next_page_url: string | null;
}

export default function Index({ products: paginatedProducts }: { products: Pagination }) {
    return (
        <>
            <Head title="Products" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h2 className="text-2xl font-bold tracking-tight">Products</h2>
                        <p className="text-muted-foreground">Manage your products and sync with Shopify.</p>
                    </div>
                    <Link href={products.create()}>
                        <Button>Add Product</Button>
                    </Link>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>All Products</CardTitle>
                        <CardDescription>A list of all products in your store.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div className="rounded-md border">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b bg-muted/50 transition-colors">
                                        <th className="h-12 px-4 text-left align-middle font-medium text-muted-foreground">ID</th>
                                        <th className="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Title</th>
                                        <th className="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Price</th>
                                        <th className="h-12 px-4 text-left align-middle font-medium text-muted-foreground">SKU</th>
                                        <th className="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Shopify Sync</th>
                                        <th className="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {paginatedProducts.data.length === 0 ? (
                                        <tr>
                                            <td colSpan={6} className="p-4 text-center text-muted-foreground">
                                                No products found.
                                            </td>
                                        </tr>
                                    ) : (
                                        paginatedProducts.data.map((product) => (
                                            <tr key={product.id} className="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                                <td className="p-4 align-middle">{product.id}</td>
                                                <td className="p-4 align-middle font-medium">{product.title}</td>
                                                <td className="p-4 align-middle">${product.price}</td>
                                                <td className="p-4 align-middle">{product.sku || '-'}</td>
                                                <td className="p-4 align-middle">
                                                    {product.shopify_id ? (
                                                        <Badge variant="default" className="bg-green-500">Synced</Badge>
                                                    ) : (
                                                        <Badge variant="secondary">Not Synced</Badge>
                                                    )}
                                                </td>
                                                <td className="p-4 align-middle text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <Link href={products.edit(product.id)}>
                                                            <Button variant="outline" size="sm">Edit</Button>
                                                        </Link>
                                                        <Link href={products.destroy(product.id)} method="delete" as="button">
                                                            <Button variant="destructive" size="sm">Delete</Button>
                                                        </Link>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

Index.layout = {
    breadcrumbs: [
        {
            title: 'Products',
            href: products.index(),
        },
    ],
};
