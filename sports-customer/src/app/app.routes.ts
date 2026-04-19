import { Routes } from '@angular/router';
import { authGuard } from './guards/auth-guard';

export const routes: Routes = [

  // Public
  { path: '', loadComponent: () => import('./pages/home/home').then(m => m.Home) },
  { path: 'products', loadComponent: () => import('./pages/products/products').then(m => m.Products) },
  { path: 'products/:id', loadComponent: () => import('./pages/product-detail/product-detail').then(m => m.ProductDetail) },
  { path: 'login', loadComponent: () => import('./pages/login/login').then(m => m.Login) },
  { path: 'register', loadComponent: () => import('./pages/register/register').then(m => m.Register) },

  // Protected (login required)
  { path: 'cart', loadComponent: () => import('./pages/cart/cart').then(m => m.Cart), canActivate: [authGuard] },
  { path: 'checkout', loadComponent: () => import('./pages/checkout/checkout').then(m => m.Checkout), canActivate: [authGuard] },
  { path: 'order-success', loadComponent: () => import('./pages/order-success/order-success').then(m => m.OrderSuccess), canActivate: [authGuard] },
  { path: 'my-orders', loadComponent: () => import('./pages/my-orders/my-orders').then(m => m.MyOrders), canActivate: [authGuard] },
  { path: 'my-orders/:id', loadComponent: () => import('./pages/order-detail/order-detail').then(m => m.OrderDetail), canActivate: [authGuard] },
  { path: 'wishlist', loadComponent: () => import('./pages/wishlist/wishlist').then(m => m.Wishlist), canActivate: [authGuard] },
  { path: 'profile', loadComponent: () => import('./pages/profile/profile').then(m => m.Profile), canActivate: [authGuard] },
  { path: 'addresses', loadComponent: () => import('./pages/addresses/addresses').then(m => m.Addresses), canActivate: [authGuard] },

  // Fallback
  { path: '**', redirectTo: '' }
];