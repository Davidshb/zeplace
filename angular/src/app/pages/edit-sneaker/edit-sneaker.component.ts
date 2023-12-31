import { Component, OnDestroy, OnInit } from '@angular/core';
import { CommonModule, NgOptimizedImage } from '@angular/common';
import { FormControl, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ShoeSize } from '@core/models/dto/shoe-size.model';
import { EditShoeDetailsInputDTO } from '@core/models/dto/shoeDetails.model';
import { PopUpService } from '@core/services/pop-up.service';
import { ShoeHttpService } from '@core/services/shoe-http.service';
import { SpinnerService } from '@core/services/spinner.service';
import { PriceFormComponent } from '@shared/components/price-form/price-form.component';
import { finalize, Subscription } from 'rxjs';

@Component({
    selector: 'app-edit-sneaker',
    standalone: true,
    imports: [CommonModule, PriceFormComponent, ReactiveFormsModule, NgOptimizedImage],
    templateUrl: './edit-sneaker.component.html',
    styleUrls: ['./edit-sneaker.component.scss']
})
export class EditSneakerComponent implements OnInit, OnDestroy {
    protected shoeDetails!: EditShoeDetailsInputDTO;

    protected form: FormGroup;

    protected changed = false;

    protected sizes: ShoeSize[];

    private originalData!: string;

    private formValueChangesSubscription!: Subscription;


    constructor(
        private readonly _route: ActivatedRoute,
        private readonly _router: Router,
        private readonly _spinnerService: SpinnerService,
        private readonly _shoeHttpService: ShoeHttpService,
        private readonly _popUpService: PopUpService
    ) {
        this.sizes = Object.values(ShoeSize);
    }

    ngOnInit() {
        this._route.data.subscribe(({shoeDetails}) => {
            this.shoeDetails = shoeDetails;
            this.form = new FormGroup({
                purchaseDate: new FormControl(shoeDetails.purchaseDate, Validators.required),
                comment: new FormControl(shoeDetails.comment),
                purchasePrice: new FormControl(shoeDetails.purchasePrice, [Validators.required, Validators.min(0)]),
                sellingDate: new FormControl(shoeDetails.sellingDate),
                sellingPrice: new FormControl(shoeDetails.sellingPrice, Validators.min(0)),
                shippingCost: new FormControl(shoeDetails.shippingCost, Validators.min(0)),
                size: new FormControl(shoeDetails.size, Validators.required)
            });

            this.form.get('sellingPrice')?.valueChanges.subscribe((value: number|null) => {
              const control = this.form.get('sellingDate')
              if (value !== null && value > 0 ) {
                if (control?.hasValidator(Validators.required) === false) {
                  control.addValidators(Validators.required)
                  control.updateValueAndValidity()
                }
              } else {
                control?.removeValidators(Validators.required)
                control?.updateValueAndValidity()
              }
            })

            this.originalData = JSON.stringify(this.form.value);

            this.formValueChangesSubscription = this.form.valueChanges.subscribe(value => {
                this.changed = JSON.stringify(value) !== this.originalData;
            });
        });
    }

    ngOnDestroy() {
        this.formValueChangesSubscription.unsubscribe();
    }

    submit() {
        this.form.markAllAsTouched();

        if (!this.form.valid) {
            this._popUpService.warn('le formulaire n\'est pas valide');
        } else {
            this._spinnerService.showGlobal();
            this._shoeHttpService.putShoeDetails(this.shoeDetails.id, this.form.value)
                .pipe(
                    finalize(() => this._spinnerService.hideGlobal())
                )
                .subscribe(res => {
                    if (res) {
                        this.changed = false;
                        this.originalData = JSON.stringify(this.form.value);
                        this._popUpService.success('les informations ont été mise à jour')
                    } else {
                        this._popUpService.warn("quelque chose s'est pas mal déroulé")
                    }
                });
        }
    }

    cancel() {
        void this._router.navigate(['/']);
    }
}
