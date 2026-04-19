import { Routes } from '@angular/router';
import { adminGuard } from './guards/admin-guard';

export const routes: Routes = [

    // Public
    { path: 'login', loadComponent: () => import('./pages/login/login').then(m => m.Login) },

    // Protected Admin Pages
    { path: '', loadComponent: () => import('./pages/dashboard/dashboard').then(m => m.Dashboard), canActivate: [adminGuard] },
    { path: 'products', loadComponent: () => import('./pages/products/products').then(m => m.Products), canActivate: [adminGuard] },
    { path: 'products/add', loadComponent: () => import('./pages/add-product/add-product').then(m => m.AddProduct), canActivate: [adminGuard] },
    { path: 'products/edit/:id', loadComponent: () => import('./pages/edit-product/edit-product').then(m => m.EditProduct), canActivate: [adminGuard] },
    { path: 'categories', loadComponent: () => import('./pages/categories/categories').then(m => m.Categories), canActivate: [adminGuard] },
    { path: 'orders', loadComponent: () => import('./pages/orders/orders').then(m => m.Orders), canActivate: [adminGuard] },
    { path: 'orders/:id', loadComponent: () => import('./pages/order-detail/order-detail').then(m => m.OrderDetail), canActivate: [adminGuard] },
    { path: 'payments', loadComponent: () => import('./pages/payments/payments').then(m => m.Payments), canActivate: [adminGuard] },

    // Fallback
    { path: '**', redirectTo: '' }
];