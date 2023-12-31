import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SaleShoeComponent } from './sale-shoe.component';

describe('ShoesComponent', () => {
  let component: SaleShoeComponent;
  let fixture: ComponentFixture<SaleShoeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SaleShoeComponent]
    });
    fixture = TestBed.createComponent(SaleShoeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
