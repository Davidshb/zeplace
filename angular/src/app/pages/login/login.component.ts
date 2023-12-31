import { AsyncPipe, NgForOf, NgIf } from '@angular/common';
import { HttpErrorResponse } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { FormsModule, NgForm } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ErrorCode } from '@core/enums/error-code';
import { LoginDTO } from '@core/models/dto/loginData.model';
import { TokenDTO } from '@core/models/dto/token.model';
import { AuthHttpService } from '@core/services/auth-http.service';
import { AuthService } from '@core/services/auth.service';
import { PopUpService } from '@core/services/pop-up.service';
import { SessionStorageService } from '@core/services/session-storage.service';
import { SpinnerComponent } from '@shared/spinner/spinner.component';
import { finalize } from 'rxjs';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule, AsyncPipe, NgIf, SpinnerComponent, NgForOf],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  protected loginData!: LoginDTO;

  private redirectUri!: string;

  constructor(
    private readonly _authService: AuthService,
    private readonly _authHttpService: AuthHttpService,
    private readonly _popUpService: PopUpService,
    private readonly _router: Router,
    private readonly _route: ActivatedRoute,
    private readonly _sessionStorageService: SessionStorageService
  ) {
  }

  ngOnInit() {
    this.loginData = new LoginDTO();
    this.loginData.username = this._sessionStorageService.get('username') ?? ''
    this._route.queryParams.subscribe(params => {
      this.redirectUri = decodeURIComponent(params['redirectUri'] ?? '/');
    });
  }

  login(loginForm: NgForm) {
    loginForm.control.disable();
    this._authHttpService.login(this.loginData)
      .pipe(finalize(() => loginForm.control.enable()))
      .subscribe({
        next: (response: TokenDTO) => {
          this._authService.login(response.token);
          void this._router.navigateByUrl(this.redirectUri, {replaceUrl: true});
        },
        error: (error: HttpErrorResponse) => {
          error.error.forEach((err: { errorCode: ErrorCode, errorMessage: string }) => {
            this._popUpService.onRequestError(err.errorMessage);
          });
        }
      });
  }
}
