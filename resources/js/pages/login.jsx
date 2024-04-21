import Layout from "@/components/elements/layout";
import ResetPassword from "@/components/reset-password";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { zodResolver } from "@hookform/resolvers/zod";
import { Inertia } from "@inertiajs/inertia";
import React, { useState, useEffect } from "react";
import { useForm as reactUseForm } from "react-hook-form";
import { toast } from "sonner";
import { z } from "zod";

const formSchema = z.object({
    username: z.string().min(1, "NIP / Username harus di isi."),
    password: z.string().min(1, "Password Harus di isi."),
});

export default function LoginPage({ token, error, is_password_change }) {
    const {
        register,
        handleSubmit,
        formState: { errors },
        watch,
    } = reactUseForm({
        resolver: zodResolver(formSchema),
        defaultValues: {
            username: "",
            password: "",
        },
    });
    const [openModal, setOpenModal] = useState(false);

    const submit = async (data) => {
        const { username, password } = data;
        const body = {
            username: username,
            password: password,
        };

        Inertia.post("/login", body);
    };

    useEffect(() => {
        if (error) {
            toast.error(error);
        } else if (token) {
            if (!is_password_change) {
                setOpenModal(true);
                toast.warning("Kata sandi belum diubah.");
                localStorage.setItem("token", token);
            } else {
                localStorage.setItem("token", token);
                toast.success("Berhasil masuk");
                Inertia.get("/");
            }
        }
    }, [token, error, is_password_change]);

    return (
        <Layout className={"h-screen"}>
            <div className="flex items-center justify-center gap-3 w-full h-full">
                <form
                    onSubmit={handleSubmit(submit)}
                    className="w-full max-w-xl"
                >
                    <Card>
                        <CardHeader>
                            <CardTitle className="text-2xl text-center font-semibold">
                                APSI
                            </CardTitle>
                            <CardDescription className="text-xl text-center font-semibold text-destructive">
                                {error}
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="grid gap-4">
                            <div className="grid gap-2">
                                <Label
                                    className="text-xl font-semibold"
                                    htmlFor="username"
                                >
                                    NIP / Username
                                </Label>
                                <Input
                                    id="username"
                                    type="text"
                                    name="username"
                                    {...register("username")}
                                    value={watch("username")}
                                    placeholder="Masukkan NIP / Username anda"
                                    className={cn(
                                        "text-lg p-6",
                                        errors.username &&
                                            "text-destructive border-destructive focus-visible:ring-0"
                                    )}
                                />
                                {errors.username && (
                                    <h4 className="text-destructive text-base">
                                        {errors.username.message}
                                    </h4>
                                )}
                            </div>
                            <div className="grid gap-2">
                                <Label
                                    className="text-xl font-semibold"
                                    htmlFor="password"
                                >
                                    Kata Sandi
                                </Label>
                                <Input
                                    {...register("password")}
                                    id="password"
                                    type="password"
                                    value={watch("password")}
                                    className={cn(
                                        "text-lg p-6",
                                        errors.password &&
                                            "text-destructive border-destructive focus-visible:ring-0"
                                    )}
                                    placeholder="Masukkan Password Anda"
                                />
                                {errors.password && (
                                    <h4 className="text-destructive text-base">
                                        {errors.password.message}
                                    </h4>
                                )}
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button className="w-full" type="submit">
                                Masuk
                            </Button>
                        </CardFooter>
                    </Card>
                </form>
            </div>
            <ResetPassword openModal={openModal} setOpenModal={setOpenModal} />
        </Layout>
    );
}
