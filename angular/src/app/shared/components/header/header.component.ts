import {Component, Input} from '@angular/core';
import { CommonModule } from '@angular/common';
import {RouterLink} from "@angular/router";
import {AuthService} from "@core/services/auth.service";

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {
  constructor(
    protected readonly _authService: AuthService
  ) {
  }
}
