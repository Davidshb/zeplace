import { Injectable } from '@angular/core';
import swal from 'sweetalert2';

@Injectable({
  providedIn: 'root'
})
export class PopUpService {

  private _toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    showCloseButton: true
  })

  private _confirm = swal.mixin({
    icon: 'warning',
    showDenyButton: true,
    confirmButtonText: 'Oui',
    confirmButtonColor: '#CD3C14',
    denyButtonText: 'Non',
    denyButtonColor: '#527EDB'
  })

  public onRequestError(title: string) {
     void this._toast.fire({
      icon: 'error',
      titleText: title
    })
  }

  public warn(title: string) {
    void this._toast.fire({
      icon: 'warning',
      titleText: title
    })
  }

  public success(title: string) {
    void this._toast.fire({
      icon: 'success',
      titleText: title
    })
  }

  public confirm(title: string, content: string = '') {
    return this._confirm.fire<boolean>({
      titleText: title,
      showDenyButton: true,
      icon: 'warning',
      confirmButtonText: 'Oui',
      denyButtonText: 'Non',
      confirmButtonColor: '#CD3C14',
      denyButtonColor: '#527EDB',
      text: content
    })
  }
}
