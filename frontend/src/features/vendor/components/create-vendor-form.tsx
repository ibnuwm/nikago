'use client';

import { useState } from 'react';

interface ServiceInput {
  name: string;
  description: string;
  starting_price: string;
}

interface CreateVendorFormProps {
  onSubmit: (data: {
    business_name: string;
    description?: string;
    phone?: string;
    email?: string;
    address?: string;
    city?: string;
    province?: string;
    services?: { name: string; description?: string; starting_price?: number }[];
  }) => void;
  onCancel: () => void;
}

export function CreateVendorForm({ onSubmit, onCancel }: CreateVendorFormProps) {
  const [businessName, setBusinessName] = useState('');
  const [description, setDescription] = useState('');
  const [phone, setPhone] = useState('');
  const [email, setEmail] = useState('');
  const [address, setAddress] = useState('');
  const [city, setCity] = useState('');
  const [province, setProvince] = useState('');
  const [services, setServices] = useState<ServiceInput[]>([]);

  const addService = () => {
    setServices([...services, { name: '', description: '', starting_price: '' }]);
  };

  const removeService = (index: number) => {
    setServices(services.filter((_, i) => i !== index));
  };

  const updateService = (index: number, field: keyof ServiceInput, value: string) => {
    const updated = [...services];
    updated[index] = { ...updated[index], [field]: value };
    setServices(updated);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    onSubmit({
      business_name: businessName,
      description: description || undefined,
      phone: phone || undefined,
      email: email || undefined,
      address: address || undefined,
      city: city || undefined,
      province: province || undefined,
      services: services.length > 0
        ? services.map((s) => ({
            name: s.name,
            description: s.description || undefined,
            starting_price: s.starting_price ? Number(s.starting_price) : undefined,
          }))
        : undefined,
    });
  };

  return (
    <form onSubmit={handleSubmit} className="rounded-lg border bg-card p-5 shadow-sm space-y-4">
      <h3 className="text-base font-semibold text-card-foreground">Add Vendor</h3>

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div className="sm:col-span-2">
          <label htmlFor="business_name" className="block text-sm font-medium text-foreground mb-1">
            Business Name <span className="text-red-500">*</span>
          </label>
          <input
            id="business_name"
            type="text"
            value={businessName}
            onChange={(e) => setBusinessName(e.target.value)}
            required
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
        <div className="sm:col-span-2">
          <label htmlFor="description" className="block text-sm font-medium text-foreground mb-1">
            Description
          </label>
          <textarea
            id="description"
            value={description}
            onChange={(e) => setDescription(e.target.value)}
            rows={3}
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
        <div>
          <label htmlFor="phone" className="block text-sm font-medium text-foreground mb-1">
            Phone
          </label>
          <input
            id="phone"
            type="text"
            value={phone}
            onChange={(e) => setPhone(e.target.value)}
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
        <div>
          <label htmlFor="email" className="block text-sm font-medium text-foreground mb-1">
            Email
          </label>
          <input
            id="email"
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
        <div className="sm:col-span-2">
          <label htmlFor="address" className="block text-sm font-medium text-foreground mb-1">
            Address
          </label>
          <input
            id="address"
            type="text"
            value={address}
            onChange={(e) => setAddress(e.target.value)}
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
        <div>
          <label htmlFor="city" className="block text-sm font-medium text-foreground mb-1">
            City
          </label>
          <input
            id="city"
            type="text"
            value={city}
            onChange={(e) => setCity(e.target.value)}
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
        <div>
          <label htmlFor="province" className="block text-sm font-medium text-foreground mb-1">
            Province
          </label>
          <input
            id="province"
            type="text"
            value={province}
            onChange={(e) => setProvince(e.target.value)}
            className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
          />
        </div>
      </div>

      <div className="border-t pt-4 space-y-3">
        <div className="flex items-center justify-between">
          <h4 className="text-sm font-medium text-foreground">Services</h4>
          <button
            type="button"
            onClick={addService}
            className="text-xs text-primary hover:text-primary/80 transition-colors"
          >
            + Add Service
          </button>
        </div>
        {services.map((svc, index) => (
          <div key={index} className="flex items-start gap-2 rounded-md border bg-muted/30 p-3">
            <div className="flex-1 space-y-2">
              <input
                type="text"
                value={svc.name}
                onChange={(e) => updateService(index, 'name', e.target.value)}
                placeholder="Service name"
                className="block w-full rounded-md border border-border bg-background px-2 py-1 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary"
              />
              <input
                type="text"
                value={svc.description}
                onChange={(e) => updateService(index, 'description', e.target.value)}
                placeholder="Description (optional)"
                className="block w-full rounded-md border border-border bg-background px-2 py-1 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary"
              />
              <input
                type="number"
                value={svc.starting_price}
                onChange={(e) => updateService(index, 'starting_price', e.target.value)}
                placeholder="Starting price (optional)"
                min={0}
                className="block w-full rounded-md border border-border bg-background px-2 py-1 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary"
              />
            </div>
            <button
              type="button"
              onClick={() => removeService(index)}
              className="text-xs text-red-500 hover:text-red-700 transition-colors mt-1 shrink-0"
            >
              Remove
            </button>
          </div>
        ))}
      </div>

      <div className="flex items-center justify-end gap-2 border-t pt-4">
        <button
          type="button"
          onClick={onCancel}
          className="inline-flex h-8 items-center justify-center gap-1 rounded-md border border-border bg-background px-3 text-xs font-medium text-foreground transition-all hover:bg-muted"
        >
          Cancel
        </button>
        <button
          type="submit"
          className="inline-flex h-8 items-center justify-center gap-1 rounded-md bg-primary px-3 text-xs font-medium text-primary-foreground transition-all hover:bg-primary/80"
        >
          Create Vendor
        </button>
      </div>
    </form>
  );
}
