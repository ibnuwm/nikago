'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { Input } from '@/components/ui/input';
import { useTemplates, useTemplateCategories, useFavoriteTemplate, useUnfavoriteTemplate } from '@/features/template/hooks/use-templates';
import { TemplateCard } from '@/features/template/components/template-card';
import { useAuthStore } from '@/stores/auth-store';

export default function TemplatesPage() {
  const [search, setSearch] = useState('');
  const [categoryFilter, setCategoryFilter] = useState('');
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: templatesData, isLoading } = useTemplates({
    search,
    category: categoryFilter,
  });

  const { data: categories } = useTemplateCategories();
  const favoriteTemplate = useFavoriteTemplate();
  const unfavoriteTemplate = useUnfavoriteTemplate();

  const templates = templatesData?.data ?? [];

  if (!token) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Templates</h1>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
          <Input
            placeholder="Search templates..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="max-w-sm"
          />
          <select
            value={categoryFilter}
            onChange={(e) => setCategoryFilter(e.target.value)}
            className="rounded-md border bg-background px-3 py-2 text-sm"
          >
            <option value="">All Categories</option>
            {categories?.map((category) => (
              <option key={category} value={category}>
                {category.charAt(0).toUpperCase() + category.slice(1)}
              </option>
            ))}
          </select>
        </div>

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Loading templates...</p>
          </div>
        ) : templates.length === 0 ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">No templates found.</p>
          </div>
        ) : (
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            {templates.map((template) => (
              <TemplateCard
                key={template.id}
                template={template}
                onUse={(uuid) => {
                  router.push(`/invitations/create?template=${uuid}`);
                }}
                onFavorite={(uuid) => favoriteTemplate.mutate(uuid)}
                onUnfavorite={(uuid) => unfavoriteTemplate.mutate(uuid)}
              />
            ))}
          </div>
        )}
      </main>
    </div>
  );
}
