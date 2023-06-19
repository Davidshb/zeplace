import {Component, OnInit} from '@angular/core';
import {FormsModule, NgForm} from "@angular/forms";
import {AuthService} from "@core/services/auth.service";
import {AsyncPipe, NgForOf, NgIf} from "@angular/common";
import {SpinnerComponent} from "@shared/spinner/spinner.component";
import {AuthHttpService} from "@core/services/auth-http.service";
import {LoginDTO} from "@core/models/dto/loginData.model";
import {HttpErrorResponse} from "@angular/common/http";
import {ErrorCode} from "@core/enums/error-code";

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule, AsyncPipe, NgIf, SpinnerComponent, NgForOf],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  protected loginData!: LoginDTO
  protected errors: string[] = []

  protected isLoggingIn$ = this._authService.getLoggingIn()

  constructor(
    private _authService: AuthService,
    private _authHttpService: AuthHttpService
  ) {
  }

  ngOnInit() {
    this.loginData = new LoginDTO()
  }

  login(loginForm: NgForm) {
    loginForm.control.disable()
    this._authHttpService.login(this.loginData).subscribe({
    next: (response) => {
      this._authService.login(response.token)
    },
    error: (error: HttpErrorResponse) => {
      this.errors = error.error.map((err: {errorCode: ErrorCode, errorMessage: string}) => {
        return err.errorMessage
      })
    }
  })
  }
}
