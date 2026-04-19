import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { BehaviorSubject, Observable, tap } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' })
export class AuthService {
  private apiUrl = environment.apiUrl;
  private adminSubject = new BehaviorSubject<any>(this.getStoredAdmin());
  admin$ = this.adminSubject.asObservable();

  constructor(private http: HttpClient, private router: Router) { }

  login(data: any): Observable<any> {
    return this.http.post(`${this.apiUrl}/login`, data).pipe(
      tap((res: any) => {
        if (res.user.role !== 'admin') {
          throw new Error('Access denied. Not an admin.');
        }
        this.storeSession(res);
      })
    );
  }

  logout(): Observable<any> {
    return this.http.post(`${this.apiUrl}/logout`, {}).pipe(
      tap(() => this.clearSession())
    );
  }

  private storeSession(res: any) {
    localStorage.setItem('admin_token', res.token);
    localStorage.setItem('admin_user', JSON.stringify(res.user));
    this.adminSubject.next(res.user);
  }

  private clearSession() {
    localStorage.removeItem('admin_token');
    localStorage.removeItem('admin_user');
    this.adminSubject.next(null);
    this.router.navigate(['/login']);
  }

  private getStoredAdmin() {
    const user = localStorage.getItem('admin_user');
    return user ? JSON.parse(user) : null;
  }

  isLoggedIn(): boolean {
    return !!localStorage.getItem('admin_token');
  }

  getAdmin() {
    return this.getStoredAdmin();
  }
}