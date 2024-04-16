import React from "react";
import { z } from "zod";
import axios from "axios";
import { Dialog, DialogContent } from "@/components/ui/dialog";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
    CardTitle,
} from "./ui/card";
import { Label } from "./ui/label";
import { Input } from "./ui/input";
import { cn } from "@/lib/utils";
import { Button } from "./ui/button";
import { Inertia } from "@inertiajs/inertia";
import { toast } from "sonner";

const formPasswordSchema = z
    .object({
        password: z.string().min(1, "Password harus di isi."),
        confirm_password: z.string(),
    })
    .refine((data) => data.password === data.confirm_password, {
        message: "Password tidak sesuai.",
        path: ["confirm_password"],
    });

export default function ResetPassword({ openModal, setOpenModal }) {
    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
    } = useForm({
        resolver: zodResolver(formPasswordSchema),
        defaultValues: {
            password: "",
            confirm_password: "",
        },
    });

    const submitResetPassword = async (data) => {
        const { password } = data;
        const fResetPasswordData = new FormData();
        fResetPasswordData.append("password", password);
        await axios
            .put(
                "/api/v1/auth/reset-password",
                {
                    password: data.password,
                },
                {
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                }
            )
            .then((res) => {
                if (res.data.status == "success") {
                    Inertia.get("/");
                    toast.success("Kata sandi berhasil diubah.");
                }
            })
            .catch((err) => {
                console.log(err.response);
            });
    };

    return (
        <Dialog open={openModal} onOpenChange={setOpenModal}>
            <DialogContent className="sm:max-w-[425px]">
                <form
                    onSubmit={handleSubmit(submitResetPassword)}
                    className="w-full max-w-xl"
                >
                    <Card className="border-none">
                        <CardHeader>
                            <CardTitle className="text-2xl text-center font-semibold">
                                Ubah Kata Sandi
                            </CardTitle>
                        </CardHeader>
                        <CardContent className="grid gap-4">
                            <div className="grid gap-2">
                                <Label
                                    className="text-xl font-semibold"
                                    htmlFor="username"
                                >
                                    Kata Sandi Baru
                                </Label>
                                <Input
                                    id="username"
                                    type="text"
                                    name="username"
                                    {...register("password")}
                                    value={watch("password")}
                                    className={cn(
                                        "text-lg p-6",
                                        errors.password &&
                                            "text-destructive border-destructive focus-visible:ring-0"
                                    )}
                                />
                                {errors.password && (
                                    <h4 className="text-destructive text-base">
                                        {errors.password.message}
                                    </h4>
                                )}
                            </div>
                            <div className="grid gap-2">
                                <Label
                                    className="text-xl font-semibold"
                                    htmlFor="password"
                                >
                                    Konfirmasi Kata Sandi Baru
                                </Label>
                                <Input
                                    id="password"
                                    type="password"
                                    {...register("confirm_password")}
                                    value={watch("confirm_password")}
                                    className={cn(
                                        "text-lg p-6",
                                        errors.confirm_password &&
                                            "text-destructive border-destructive focus-visible:ring-0"
                                    )}
                                />
                                {errors.confirm_password && (
                                    <h4 className="text-destructive text-base">
                                        {errors.confirm_password.message}
                                    </h4>
                                )}
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button className="w-full" type="submit">
                                Save changes
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </DialogContent>
        </Dialog>
    );
}
