import {Component} from '@angular/core';
import {RouterOutlet} from '@angular/router';
import {SpinnerComponent} from "@shared/spinner/spinner.component";
import {AsyncPipe, NgIf} from "@angular/common";
import {SpinnerService} from "@core/services/spinner.service";
import {HeaderComponent} from "@shared/components/header/header.component";

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, SpinnerComponent, NgIf, AsyncPipe, HeaderComponent],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  protected showSpinner$ = this._spinnerService.getGlobalSpinner()

  constructor(
    private _spinnerService: SpinnerService
  ) {
  }
}
