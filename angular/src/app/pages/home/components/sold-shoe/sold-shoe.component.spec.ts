import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SoldShoeComponent } from './sold-shoe.component';

describe('ShoesComponent', () => {
  let component: SoldShoeComponent;
  let fixture: ComponentFixture<SoldShoeComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      imports: [SoldShoeComponent]
    });
    fixture = TestBed.createComponent(SoldShoeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
