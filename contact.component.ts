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

  formData = {
    user_id: 0,
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
    this.formData.user_id = Number(localStorage.getItem('user_id'));
  this.connectService.getContactContent().subscribe({
    next: res => this.content = res,
    error: () => console.error('Failed to load body content')
  });
  }


  submitMessage() {
  console.log('Sending message:', this.formData); // 👀
  this.connectService.sendMessage(this.formData).subscribe({
    next: (res) => {
      console.log('Message response:', res);
      alert('Message sent successfully!');
    },
    error: (err) => {
      console.error('Message send failed:', err);
      alert('Something went wrong.');
    }
  });
}


  // Add any methods or properties needed for the tips component

}
