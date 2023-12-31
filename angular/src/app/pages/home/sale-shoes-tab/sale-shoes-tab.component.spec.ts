import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SaleShoesTabComponent } from './sale-shoes-tab.component';

describe('SaleShoesTabComponent', () => {
  let component: SaleShoesTabComponent;
  let fixture: ComponentFixture<SaleShoesTabComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SaleShoesTabComponent]
    });
    fixture = TestBed.createComponent(SaleShoesTabComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
