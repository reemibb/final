import { Component } from '@angular/core';
import { ConnectService } from '../connect.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  standalone: false
})
export class RegisterComponent {
  firstname: string = '';
  lastname: string = '';
  email: string = '';
  password: string = '';
  message: string = '';
  logoUrl = '';

  constructor(
    private connectService: ConnectService,
    private router: Router
  ) {}

  signup() {
    const data = {
      firstname: this.firstname,
      lastname: this.lastname,
      email: this.email,
      password: this.password
    };

    this.connectService.signup(data).subscribe({
      next: (response) => {
        console.log('Registration response:', response); // Log complete response
        
        if (response.success) {
          this.message = 'Registration successful! Redirecting...';
          
          // Safely store user ID with error handling
          if (response.user && response.user.id) {
            localStorage.setItem('user_id', response.user.id.toString());
            console.log('User ID stored in localStorage:', response.user.id);
          } else {
            console.warn('User ID not found in response:', response);
          }
          
          // Add a small delay before navigation
          setTimeout(() => {
            console.log('Navigating to home page...');
            this.router.navigate(['/home'])
              .then(() => console.log('Navigation successful'))
              .catch(err => console.error('Navigation error:', err));
          }, 1000);
        } else {
          this.message = response.message || 'Registration failed.';
        }
      },
      error: (error) => {
        console.error('Registration error:', error);
        this.message = 'Server error occurred. Please try again later.';
      }
    });
  }

  ngOnInit(): void {
    this.connectService.getLogoUrl().subscribe(res => {
      if (res?.url) {
        this.logoUrl = res.url;
      }
    });
  }
}