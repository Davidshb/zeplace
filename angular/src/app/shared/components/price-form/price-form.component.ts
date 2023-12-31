import { Component, forwardRef, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ControlValueAccessor, FormsModule, NG_VALUE_ACCESSOR } from '@angular/forms';

@Component({
  selector: 'app-price-form',
  standalone: true,
  imports: [CommonModule, FormsModule],
  template: `
    <div class="input-group">
      <input type="text" [ngModel]="value" (focus)="onTouch()"
             (click)="selectInput($event)"
             (input)="changeValue($event)"
             class="form-control text-end"
             [ngClass]="{'is-invalid': isInvalid}"
             [id]="id"
             [disabled]="disabled"/>
      <span class="input-group-text">€</span>
    </div>
  `,
  providers: [
    {
      provide: NG_VALUE_ACCESSOR,
      useExisting: forwardRef(() => PriceFormComponent),
      multi: true
    }
  ]
})
export class PriceFormComponent implements ControlValueAccessor {

  protected value = '0.00'
  protected disabled = false

  @Input()
  public id!: string

  @Input()
  public isInvalid = false

  protected onTouch: any = () => {
  }

  private onChange: any = () => {
  }

  registerOnChange(fn: any): void {
    this.onChange = fn
  }

  registerOnTouched(fn: any): void {
    this.onTouch = fn
  }

  setDisabledState(isDisabled: boolean): void {
    this.disabled = isDisabled
  }

  writeValue(input: number): void {
    if (isNaN(input) || input < 0) {
      input = 0
    }

    this.value = this.processNumberToString(input)
  }

  protected changeValue(event: Event) {
    const target = event.target as HTMLInputElement
    let value = target.value

    if (value.length == 0) {
      value = '0'
    }

    if (value[value.length - 1] == '.') {
      value += '00'
    }

    const dotIndex = value.indexOf(('.'))

    if (dotIndex !== -1) {
      if (value.length == 2) {
        value = `0${value}`
      }

      value = value.replace('.', '')
      value = value.slice(0, value.length - 2) + '.' + value.slice(value.length - 2)
    }

    const floatValue = Number.parseFloat(value)

    if (isNaN(floatValue)) {
      target.value = this.value
      return
    }

    value = this.processNumberToString(floatValue);
    this.value = value
    target.value = value
    this.onChange(floatValue)
  }

  protected selectInput(event: Event) {
    event.preventDefault()
    const target = event.target as HTMLInputElement
    const index = target.value.length
    target.setSelectionRange(index, index, 'forward')
  }

  private processNumberToString(res: number): string {
    let value = String(Math.floor(res * 100) / 100)
    if (value.indexOf('.') == -1) {
      value += '.00'
    }

    if (value.indexOf('.') == value.length - 2) {
      value += '0'
    }

    return value
  }
}
