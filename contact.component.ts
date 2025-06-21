import { Component, OnInit } from '@angular/core';
import { ConnectService } from '../connect.service';

@Component({
  selector: 'app-tips',
  standalone: false,
  templateUrl: './contact.component.html',
  styleUrl: './contact.component.css'
})
export class ContactComponent implements OnInit {

  content: any = {};
  userId: number = 0; 
  showAlert = false;

  formData = {
  name: '',
  email: '',
  subject: '',
  message: ''
};


  constructor(
    private connectService: ConnectService
  ) {}

  ngOnInit(): void {
     this.connectService.getContactContent().subscribe({
      next: res => this.content = res,
      error: () => console.error('Failed to load body content')
    });
  this.connectService.getContactContent().subscribe({
    next: res => this.content = res,
    error: () => console.error('Failed to load body content')
  });
  this.userId = Number(localStorage.getItem('user_id'));
  }


  submitMessage() {
  this.connectService.sendMessage(this.userId, this.formData.name, this.formData.email, this.formData.subject, this.formData.message).subscribe(
    (res: any) => {
      if (res.success) {
        this.showAlert = true;
        setTimeout(() => this.showAlert = false, 5000); // Auto-dismiss after 5s
        this.formData.name = ''; // Clear field
        this.formData.email = ''; // Clear field
        this.formData.subject = ''; // Clear field
        this.formData.message = ''; // Clear field
      } else {
        alert(res.message || "Message failed to send.");
      }
    },
    () => alert("Server error during sending.")
  );
}
}


  // Add any methods or properties needed for the tips component


